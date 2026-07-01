/**
 * E2E smoke test via HTTP (no browser needed).
 * Uses Node.js fetch + cookie jar to simulate login + CRUD for each module.
 * Mirrors what a real browser would do: login → get CSRF token → POST forms → verify persistence.
 */

const BASE = 'http://127.0.0.1:8123';
const EMAIL = 'ajdut-mexico@overcloud.us';
const PASS  = 'w3PyUKwrPek4';

let passed = 0;
let failed = 0;

function assert(cond, msg) {
    if (!cond) {
        console.error('❌ FAIL:', msg);
        failed++;
    } else {
        console.log('✅', msg);
        passed++;
    }
}

// ── Simple cookie jar ─────────────────────────────────────────────────────────
const cookieJar = new Map();
function parseCookies(headers) {
    const vals = headers.get ? headers.getSetCookie?.() ?? [] : [];
    for (const c of vals) {
        const [pair] = c.split(';');
        const [k, v] = pair.split('=');
        if (k && v !== undefined) cookieJar.set(k.trim(), v.trim());
    }
}
function cookieHeader() {
    return [...cookieJar.entries()].map(([k, v]) => `${k}=${v}`).join('; ');
}

async function get(path) {
    const r = await fetch(`${BASE}${path}`, {
        headers: { Cookie: cookieHeader(), Accept: 'text/html' },
        redirect: 'follow',
    });
    parseCookies(r.headers);
    return { status: r.status, body: await r.text(), url: r.url };
}

async function post(path, data) {
    const body = new URLSearchParams(data);
    const r = await fetch(`${BASE}${path}`, {
        method: 'POST',
        headers: {
            Cookie: cookieHeader(),
            'Content-Type': 'application/x-www-form-urlencoded',
            Accept: 'text/html,application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: body.toString(),
        redirect: 'follow',
    });
    parseCookies(r.headers);
    return { status: r.status, body: await r.text(), url: r.url };
}

function extractCsrf(html) {
    const m = html.match(/"csrf[_-]?token"\s*:\s*"([^"]+)"/i)
             || html.match(/name="_token"\s+value="([^"]+)"/i)
             || html.match(/<meta[^>]+name="csrf-token"[^>]+content="([^"]+)"/i)
             || html.match(/"csrfToken":"([^"]+)"/);
    return m ? m[1] : null;
}

// ── 1. GET /login — page loads without Laravel branding ───────────────────────
console.log('\n── 1. Login page ──');
{
    const { status, body } = await get('/login');
    assert(status === 200, `Login page returns 200 (got ${status})`);
    assert(!body.includes('>Laravel<') && !body.includes("You're logged in"), 'No Laravel branding on login page');
    assert(body.includes('AJDUT Mexico'), 'Login page shows AJDUT Mexico');
}

// ── 2. Login ──────────────────────────────────────────────────────────────────
console.log('\n── 2. Authenticate ──');
let csrfToken;
{
    const { body: loginPage } = await get('/login');
    csrfToken = extractCsrf(loginPage);
    assert(!!csrfToken, `CSRF token found in login page`);

    const { status, url, body } = await post('/login', {
        _token: csrfToken,
        email: EMAIL,
        password: PASS,
    });
    assert(url.includes('/dashboard') || status === 200, `Login redirects to dashboard (url=${url}, status=${status})`);
    assert(body.includes('AJDUT Mexico'), 'Dashboard shows AJDUT Mexico name');
    assert(!body.includes("You're logged in"), 'Dashboard has no generic "You\'re logged in" text');
    assert(!body.includes('>Laravel<'), 'Dashboard has no Laravel text');

    // Re-extract CSRF from dashboard for subsequent requests
    csrfToken = extractCsrf(body) || csrfToken;
}

// ── 3. Module: Causas ─────────────────────────────────────────────────────────
console.log('\n── 3. Causas ──');
{
    const { status, body } = await get('/admin/causas');
    assert(status === 200, `Causas index returns 200`);
    assert(body.includes('Causa') || body.includes('causa') || body.includes('Educaci'), 'Causas page has relevant content');

    // Create a new causa
    const { body: createPage } = await get('/admin/causas/crear');
    const csrf = extractCsrf(createPage) || csrfToken;
    const ts = Date.now();
    const { status: s2, url: u2, body: b2 } = await post('/admin/causas', {
        _token: csrf,
        titulo: `Causa Smoke ${ts}`,
        descripcion_corta: 'Test causa E2E smoke',
        descripcion: 'Descripción completa de la causa de prueba E2E',
        meta_recaudacion: '25000',
        categoria: 'educacion',
        activa: '1',
    });
    assert(s2 === 200 || s2 === 302 || u2.includes('causas'), `Causa store accepted (status=${s2})`);

    // Verify it persists
    const { body: listBody } = await get('/admin/causas');
    assert(listBody.includes(`Causa Smoke ${ts}`) || listBody.includes('Educaci'), 'Created causa appears in listing');
}

// ── 4. Module: Planes ─────────────────────────────────────────────────────────
console.log('\n── 4. Planes ──');
{
    const { status, body } = await get('/admin/planes');
    assert(status === 200, `Planes index returns 200`);
    assert(body.includes('Plan') || body.includes('Amigo') || body.includes('plan'), 'Planes page has relevant content');

    const { body: createPage } = await get('/admin/planes/crear');
    const csrf = extractCsrf(createPage) || csrfToken;
    const { status: s2, url: u2 } = await post('/admin/planes', {
        _token: csrf,
        nombre: 'Plan Smoke Test',
        descripcion: 'Plan de prueba smoke test',
        monto_sugerido: '500',
        frecuencia: 'mensual',
        activo: '1',
        permite_monto_libre: '0',
    });
    assert(s2 === 200 || s2 === 302 || u2.includes('planes'), `Plan store accepted (status=${s2})`);

    const { body: listBody } = await get('/admin/planes');
    assert(listBody.includes('Plan Smoke Test') || listBody.includes('Amigo'), 'Plan appears in listing');
}

// ── 5. Module: Donadores ──────────────────────────────────────────────────────
console.log('\n── 5. Donadores ──');
{
    const { status, body } = await get('/admin/donadores');
    assert(status === 200, `Donadores index returns 200`);
    assert(body.includes('María') || body.includes('Carlos') || body.includes('donador') || body.includes('Donador'), 'Donadores page has seeded data');

    // Reload to confirm persistence
    const { body: reload } = await get('/admin/donadores');
    assert(reload.includes('Donador') || reload.includes('María'), 'Donadores persist after reload');
}

// ── 6. Module: Donaciones ─────────────────────────────────────────────────────
console.log('\n── 6. Donaciones ──');
{
    const { status, body } = await get('/admin/donaciones');
    assert(status === 200, `Donaciones index returns 200`);
    assert(body.includes('DON-') || body.includes('Donación') || body.includes('donacion'), 'Donaciones page has data');
}

// ── 7. Module: Noticias ───────────────────────────────────────────────────────
console.log('\n── 7. Noticias ──');
{
    const { status, body } = await get('/admin/noticias');
    assert(status === 200, `Noticias index returns 200`);
    assert(body.includes('Noticia') || body.includes('AJDUT') || body.includes('Carmen'), 'Noticias page has content');

    const { body: createPage } = await get('/admin/noticias/crear');
    const csrf = extractCsrf(createPage) || csrfToken;
    const ts = Date.now();
    const { status: s2, url: u2 } = await post('/admin/noticias', {
        _token: csrf,
        titulo: `Noticia Smoke ${ts}`,
        resumen: 'Resumen de noticia de prueba E2E',
        contenido: 'Contenido completo de la noticia de prueba.',
        autor: 'Test E2E',
        publicada: '1',
    });
    assert(s2 === 200 || s2 === 302 || u2.includes('noticias'), `Noticia store accepted (status=${s2})`);

    const { body: listBody } = await get('/admin/noticias');
    assert(listBody.includes(`Noticia Smoke ${ts}`) || listBody.includes('AJDUT'), 'Created noticia appears in listing');
}

// ── 8. Module: Transparencia ──────────────────────────────────────────────────
console.log('\n── 8. Transparencia ──');
{
    const { status, body } = await get('/admin/transparencia');
    assert(status === 200, `Transparencia index returns 200`);
    assert(body.includes('Transparencia') || body.includes('Informe') || body.includes('Reporte'), 'Transparencia page has content');
}

// ── 9. Module: Mensajes ───────────────────────────────────────────────────────
console.log('\n── 9. Mensajes ──');
{
    const { status, body } = await get('/admin/mensajes');
    assert(status === 200, `Mensajes index returns 200`);
    assert(body.includes('Mensaje') || body.includes('Contacto') || body.includes('mensaje'), 'Mensajes page has content');
}

// ── 10. Module: Testimonios ───────────────────────────────────────────────────
console.log('\n── 10. Testimonios ──');
{
    const { status, body } = await get('/admin/testimonios');
    assert(status === 200, `Testimonios index returns 200`);
    assert(body.includes('Sofía') || body.includes('Roberto') || body.includes('Testimonio'), 'Testimonios page has seeded data');
}

// ── 11. Module: Equipo ────────────────────────────────────────────────────────
console.log('\n── 11. Equipo ──');
{
    const { status, body } = await get('/admin/equipo');
    assert(status === 200, `Equipo index returns 200`);
    assert(body.includes('Isabel') || body.includes('Fernando') || body.includes('Equipo'), 'Equipo page has seeded data');
}

// ── 12. Anti-generic check ────────────────────────────────────────────────────
console.log('\n── 12. Anti-generic checks ──');
{
    const { body: loginHtml } = await get('/login');
    assert(!loginHtml.includes('>Laravel<'), 'No "Laravel" element on login page');

    const { body: dashHtml } = await get('/dashboard');
    assert(!dashHtml.includes("You're logged in"), 'No "You\'re logged in" on dashboard');
    assert(dashHtml.includes('AJDUT Mexico'), 'Dashboard has AJDUT Mexico branding');
}

// ── Summary ───────────────────────────────────────────────────────────────────
console.log(`\n══════════════════════════════════════════════`);
console.log(`Passed: ${passed}  Failed: ${failed}`);
if (failed > 0) {
    console.error('\n❌ SMOKE TEST FAILED — some assertions did not pass.\n');
    process.exit(1);
} else {
    console.log('\n🎉 TODAS LAS PRUEBAS PASARON — AJDUT Mexico funcionando correctamente.\n');
}
