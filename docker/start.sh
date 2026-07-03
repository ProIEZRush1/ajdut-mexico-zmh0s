#!/usr/bin/env sh
# Production entrypoint. Deliberately NO `set -e`: a migrate/seed hiccup or a
# slow/unready Postgres must never crash the container or block serving.
cd /app || exit 1

# 1. Ensure a .env exists.
[ -f .env ] || cp .env.production .env

# 2. Ensure a valid APP_KEY in .env BEFORE config:cache.
#    Prefer the Coolify-injected APP_KEY (the REAL key that matches the
#    DB-encrypted data). Only generate a fresh one if nothing valid exists.
#    Always write the resolved key into .env so it survives container
#    recreation (a fresh container resets .env from the image otherwise).
if echo "$APP_KEY" | grep -q "^base64:"; then
    sed -i '/^APP_KEY=/d' .env
    echo "APP_KEY=$APP_KEY" >> .env
elif ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    php artisan key:generate --force || true
fi
APP_KEY="$(grep '^APP_KEY=' .env | head -1 | cut -d '=' -f2-)"
export APP_KEY

# 2b. Sync injected runtime secrets into .env so config:cache bakes them
#     (config:cache reads .env; a fresh container has no STRIPE_* lines).
for _k in STRIPE_KEY STRIPE_SECRET STRIPE_WEBHOOK_SECRET; do
    eval _v=\"\$$_k\"
    if [ -n "$_v" ]; then
        sed -i "/^$_k=/d" .env
        echo "$_k=$_v" >> .env
    fi
done

# 3. SQLite-only: ensure the database file and its directory exist.
#    When DB_CONNECTION is pgsql/mysql, skip this block entirely so we never
#    export a file path as DB_DATABASE for a server-based engine.
_DB_CONN="${DB_CONNECTION:-sqlite}"
if [ "$_DB_CONN" = "sqlite" ]; then
    DB_DATABASE="${DB_DATABASE:-/app/database/database.sqlite}"
    export DB_DATABASE
    _DB_DIR="$(dirname "$DB_DATABASE")"
    mkdir -p "$_DB_DIR" 2>/dev/null || true
    [ -f "$DB_DATABASE" ] || touch "$DB_DATABASE" 2>/dev/null || true
    chmod -R ug+rwX "$_DB_DIR" 2>/dev/null || true
fi

# Make runtime dirs writable (best effort).
mkdir -p storage/framework/cache storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || true

# 4. Migrate + seed in the BACKGROUND, best-effort with a retry loop, so a
#    slow/unready database never blocks or crashes serving.
(
    i=1
    while [ "$i" -le 40 ]; do
        if php artisan migrate --force 2>&1; then
            php artisan db:seed --force 2>&1 || true
            echo "[entrypoint] migrate/seed completed on attempt $i"
            break
        fi
        echo "[entrypoint] migrate attempt $i failed; retrying in 3s..."
        i=$((i + 1))
        sleep 3
    done
) &

# 5. Cache config/routes/views (uses the exported APP_KEY). Best effort.
php artisan config:cache 2>/dev/null || true
php artisan route:cache  2>/dev/null || true
php artisan view:cache   2>/dev/null || true

# 6. Serve under an auto-respawn loop: if a request ever crashes the dev server, it restarts
#    immediately, so the app is never permanently down (no `set -e`, so the loop always continues).
while true; do
    php artisan serve --host 0.0.0.0 --port 8080
    echo "[entrypoint] server exited (code $?); restarting in 1s..."
    sleep 1
done
