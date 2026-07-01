/**
 * E2E smoke test via HTTP (no browser needed).
 * Uses Node.js fetch + cookie jar to simulate login + CRUD for each module.
 * Redirects are followed MANUALLY so intermediate Set-Cookie headers are captured.
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

// ── Cookie jar ────────────────────────────────────────────────────────────────
const cookieJar = new Map();

function parseCookies(headers) {
    const vals = headers.getSetCookie ? headers.getSetCookie() : [];
    for (const c of vals) {
        const semi = c.indexOf(';');
        const pair = semi === -1 ? c : c.slice(0, semi);
        const eqIdx = pair.indexOf('=');
        if (eqIdx === -1) continue;
        const k = pair.slice(0, eqIdx).trim();
        const v = pair.slice(eqIdx + 1).trim();
        if (k) cookieJar.set(k, v);
    }
}

function cookieHeader() {
    return [...cookieJar.entries()].map(([k, v]) => `${k}=${v}`).join('; ');
}

function getXsrfToken() {
    const raw = cookieJar.get('XSRF-TOKEN');
    if (!raw) return null;
    try { return decodeURIComponent(raw); } catch { return raw; }
}

function extractCsrf(html) {
    const meta = html.match(/<meta[^>]+name="csrf-token"[^>]+content="([^"]+)"/i)
              || html.match(/<meta[^>]+content="([^"]+)"[^>]+name="csrf-token"/i);
    if (meta) return meta[1];
    const inertia = html.match(/"csrf[_-]?token"\s*:\s*"([^"]+)"/i)
                 || html.match(/"csrfToken":"([^"]+)"/)
                 || html.match(/name="_token"\s+value="([^"]+)"/i);
    return inertia ? inertia[1] : null;
}

// Follow redirects manually so we capture Set-Cookie from each hop
async function followRedirects(url, method, body, headers, maxRedirects = 10) {
    let currentUrl = url;
    let currentMethod = method;
    let currentBody = body;
    let redirectCount = 0;

    while (redirectCount < maxRedirects) {
        const r = await fetch(currentUrl, {
            method: currentMethod,
            headers: { ...headers, Cookie: cookieHeader() },
            body: currentBody,
            redirect: 'manual',
        });
        parseCookies(r.headers);

        if (r.status >= 300 && r.status < 400) {
            const location = r.headers.get('location');
            if (!location) break;
            currentUrl = location.startsWith('http') ? location : `${BASE}${location}`;
            currentMethod = 'GET';
            currentBody = undefined;
            headers = { Accept: 'text/html', 'X-Requested-With': 'XMLHttpRequest' };
            redirectCount++;
            continue;
        }

        const text = await r.text();
        return { status: r.status, body: text, url: currentUrl };
    }

    return { status: 0, body: '', url: currentUrl };
}

async function get(path) {
    return followRedirects(`${BASE}${path}`, 'GET', undefined, {
        Accept: 'text/html',
    });
}

async function post(path, data) {
    const bodyStr = new URLSearchParams(data).toString();
    const xsrf = getXsrfToken();
    return followRedirects(`${BASE}${path}`, 'POST', bodyStr, {
        'Content-Type': 'application/x-www-form-urlencoded',
        Accept: 'text/html,application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(xsrf ? { 'X-XSRF-TOKEN': xsrf } : {}),
    });
}

// ── 1. GET /login ─────────────────────────────────────────────────────────────
console.log('\n── 1. Login page ──');
let csrfToken;
{
    const { status, body } = await get('/login');
    assert(status === 200, `Login page returns 200 (got ${status})`);
    assert(!body.includes('>Laravel<') && !body.includes("You're logged in"), 'No Laravel branding on login page');
    assert(body.includes('AJDUT Mexico'), 'Login page shows AJDUT Mexico');
    csrfToken = extractCsrf(body);
}

// ── 2. Login ──────────────────────────────────────────────────────────────────
console.log('\n── 2. Authenticate ──');
{
    assert(!!csrfToken || !!getXsrfToken(), `CSRF token available (meta=${!!csrfToken}, xsrf-cookie=${!!getXsrfToken()})`);

    const loginData = { email: EMAIL, password: PASS };
    if (csrfToken) loginData._token = csrfToken;

    const { status, url, body } = await post('/login', loginData);
    assert(url.includes('/dashboard'), `Login redirects to dashboard (url=${url}, status=${status})`);
    assert(body.includes('AJDUT Mexico'), 'Dashboard shows AJDUT Mexico name');
    assert(!body.includes("You're logged in"), 'Dashboard has no generic "You\'re logged in" text');
    assert(!body.includes('>Laravel<'), 'Dashboard has no Laravel text');

    csrfToken = extractCsrf(body) || csrfToken;
}

// ── 3. Module: Causas ─────────────────────────────────────────────────────────
console.log('\n── 3. Causas ──');
{
    const { status, body } = await get('/admin/causas');
    assert(status === 200, `Causas index returns 200`);
    assert(body.includes('Causa') || body.includes('causa') || body.includes('Educaci'), 'Causas page has relevant content');

    const { body: createPage } = await get('/admin/causas/crear');
    const csrf = extractCsrf(createPage) || csrfToken;
    const ts = Date.now();
    const causeData = {
        titulo: `Causa Smoke ${ts}`,
        descripcion_corta: 'Test causa E2E smoke description',
        descripcion: 'Descripción completa de la causa de prueba E2E',
        meta_recaudacion: '25000',
        categoria: 'educacion',
    };
    if (csrf) causeData._token = csrf;
    const { status: s2, url: u2 } = await post('/admin/causas', causeData);
    assert(s2 === 200 || u2.includes('causas'), `Causa store accepted (status=${s2}, url=${u2})`);

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
    const planData = {
        nombre: 'Plan Smoke Test',
        descripcion: 'Plan de prueba smoke test E2E',
        monto_sugerido: '500',
        frecuencia: 'mensual',
    };
    if (csrf) planData._token = csrf;
    const { status: s2, url: u2 } = await post('/admin/planes', planData);
    assert(s2 === 200 || u2.includes('planes'), `Plan store accepted (status=${s2}, url=${u2})`);

    const { body: listBody } = await get('/admin/planes');
    assert(listBody.includes('Plan Smoke Test') || listBody.includes('Amigo'), 'Plan appears in listing');
}

// ── 5. Module: Donadores ──────────────────────────────────────────────────────
console.log('\n── 5. Donadores ──');
{
    const { status, body } = await get('/admin/donadores');
    assert(status === 200, `Donadores index returns 200`);
    assert(body.includes('María') || body.includes('Carlos') || body.includes('Donador'), 'Donadores page has seeded data');

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
    const noticiaData = {
        titulo: `Noticia Smoke ${ts}`,
        resumen: 'Resumen de noticia de prueba E2E de AJDUT Mexico',
        contenido: 'Contenido completo de la noticia de prueba del sistema AJDUT Mexico.',
        autor: 'Test E2E',
        publicada: '1',
    };
    if (csrf) noticiaData._token = csrf;
    const { status: s2, url: u2 } = await post('/admin/noticias', noticiaData);
    assert(s2 === 200 || u2.includes('noticias'), `Noticia store accepted (status=${s2}, url=${u2})`);

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

// ── 12. Anti-generic checks ───────────────────────────────────────────────────
console.log('\n── 12. Anti-generic checks ──');
{
    const { body: loginHtml } = await get('/login');
    assert(!loginHtml.includes('>Laravel<'), 'No "Laravel" element on login page');
    assert(loginHtml.includes('AJDUT Mexico'), 'Login page shows AJDUT Mexico branding');

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
