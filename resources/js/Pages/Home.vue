<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({
    causas: Array,
    planes: Array,
    testimonios: Array,
    stats: Object,
})

const { t } = useI18n()

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const pct = (r, m) => m > 0 ? Math.min(100, Math.round((r / m) * 100)) : 0

const planColors = ['from-teal-500 to-teal-700', 'from-emerald-400 to-coral-500', 'from-coral-500 to-coral-700']
const planIcons = ['🌱', '🌟', '💎']

const causaAccents = ['from-coral-500 to-coral-600', 'from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-600']

const comoAyudamos = [
    { path: 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z', titulo: 'Tarjetas para despensa', texto: 'Brindamos tarjetas con saldo para que cada familia pueda comprar alimentos en tiendas kosher con dignidad y tranquilidad.' },
    { path: 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z', titulo: 'Apoyo a familias', texto: 'Acompañamos a viudas y a sus hijos en momentos difíciles, asegurando que no enfrenten solos sus necesidades básicas.' },
    { path: 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z', titulo: 'Juntos hacemos más', texto: 'Cada aportación nos permite llegar a más hogares y brindar estabilidad a quienes más lo necesitan.' },
    { path: 'M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z', titulo: 'Sé parte del cambio', texto: 'Tu ayuda devuelve esperanza y tranquilidad a familias que están reconstruyendo su vida.' },
]

const montos = [
    { monto: 500, texto: 'Brindas apoyo para la despensa de una familia.', destacado: false },
    { monto: 1000, texto: 'Das tranquilidad y alimento a una familia.', destacado: false },
    { monto: 2000, texto: 'Cubres una tarjeta mensual completa para una familia.', destacado: true },
    { monto: 5000, texto: 'Cubres la tarjeta mensual de 2 familias completas.', destacado: false },
    { monto: 10000, texto: 'Sostienes a 5 familias completas durante el mes.', destacado: false },
]
const montoColors = ['from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-600', 'from-coral-500 to-coral-600', 'from-teal-600 to-coral-500', 'from-coral-500 to-teal-700']

const statsDisplay = computed(() => [
    { value: props.stats?.donadores ?? '0', label: t('home.stats.donors'), icon: '👥' },
    { value: fmt(props.stats?.total_recaudado ?? 0), label: props.stats?.mes ? `Recaudado en ${props.stats.mes}` : t('home.stats.raised'), icon: '💰' },
    { value: props.stats?.causas_activas ?? '0', label: t('home.stats.causes'), icon: '❤️' },
    { value: props.stats?.beneficiarios ?? '5,000+', label: t('home.stats.beneficiaries'), icon: '🤝' },
])

const staticTestimonios = [
    { nombre: 'Sara Levy', texto: 'Saber que mi aportación llega directo a una familia de la comunidad no tiene precio. Cada donativo se siente como una verdadera mitzvá.', plan: 'Padrino' },
    { nombre: 'David Cohen', texto: 'Doné para Pesaj y me llegó el comprobante al instante. Todo transparente y con el corazón. Jazak ubaruj a todo el equipo de AJDUT.', plan: 'Amigo' },
    { nombre: 'Raquel Mizrahi', texto: 'Apoyar a las familias con su despensa mes a mes me llena. Es una forma sencilla y segura de cumplir con la tzedaká.', plan: 'Benefactor' },
]

const displayTestimonios = computed(() => {
    const conTexto = (props.testimonios ?? []).filter(t => (t.texto ?? t.contenido))
    return conTexto.length ? conTexto : staticTestimonios
})

const staticCausas = [
    { id: null, titulo: 'Alimentación Infantil', descripcion_corta: 'Garantizamos tres comidas diarias a niños en situación de vulnerabilidad en comunidades rurales.', meta_recaudacion: 200000, recaudado: 142000, categoria: 'alimentación', beneficiarios: 850 },
    { id: null, titulo: 'Educación para Todos', descripcion_corta: 'Becas, útiles y apoyo educativo para jóvenes de escasos recursos que sueñan con un mejor futuro.', meta_recaudacion: 350000, recaudado: 210000, categoria: 'educación', beneficiarios: 320 },
    { id: null, titulo: 'Salud Comunitaria', descripcion_corta: 'Brigadas médicas y medicamentos gratuitos para comunidades sin acceso a servicios de salud.', meta_recaudacion: 500000, recaudado: 389000, categoria: 'salud', beneficiarios: 1200 },
]

const displayCausas = computed(() => props.causas?.length ? props.causas.slice(0, 3) : staticCausas)

const staticPlanes = [
    { id: null, nombre: 'Amigo', monto_sugerido: 150, frecuencia: 'mensual', descripcion: 'Apoya con una donación mensual simbólica y recibe actualizaciones de impacto.', beneficios: ['Newsletter mensual', 'Reporte de impacto trimestral', 'Certificado de donador'], destacado: false },
    { id: null, nombre: 'Padrino', monto_sugerido: 500, frecuencia: 'mensual', descripcion: 'Tu aportación mensual patrocina directamente a una familia o proyecto específico.', beneficios: ['Todo lo del plan Amigo', 'Historia de impacto personalizada', 'Invitación a eventos anuales', 'Recibo fiscal deducible'], destacado: true },
    { id: null, nombre: 'Benefactor', monto_sugerido: 2000, frecuencia: 'mensual', descripcion: 'Conviértete en pilar de nuestra institución y recibe reconocimiento especial.', beneficios: ['Todo lo del plan Padrino', 'Placa de reconocimiento', 'Reunión anual con directivos', 'Visita a proyectos apoyados'], destacado: false },
]

const displayPlanes = computed(() => props.planes?.length ? props.planes.slice(0, 3) : staticPlanes)
</script>

<template>
    <Head title="AJDUT México — Apoyo a viudas y huérfanos de México" />
    <PublicLayout>
        <!-- HERO / PORTADA -->
        <section class="relative overflow-hidden bg-[#faf6ee]">
            <span class="absolute top-4 right-5 sm:right-8 font-serif text-lg text-teal-900/70" dir="rtl">בס״ד</span>
            <div class="relative mx-auto max-w-3xl px-4 sm:px-6 pt-14 sm:pt-16 text-center">
                <img src="/logo-ajdut.png" alt="AJDUT México" class="mx-auto h-40 sm:h-56 w-auto object-contain" />
                <h1 class="mt-5 font-serif text-5xl sm:text-6xl font-bold uppercase tracking-wide text-[#c19a54]">Asóciate</h1>
                <p class="mt-1 font-serif text-3xl sm:text-4xl font-semibold text-teal-900">a esta gran mitzvá</p>
                <div class="my-6 flex items-center justify-center gap-4 text-teal-900">
                    <span class="h-px w-16 bg-teal-900/40"></span>
                    <span class="text-lg leading-none">♥</span>
                    <span class="h-px w-16 bg-teal-900/40"></span>
                </div>
                <p class="mx-auto max-w-xl font-serif text-xl sm:text-2xl leading-relaxed text-teal-900">
                    Hay viudas que sonríen frente a sus hijos… mientras por dentro no saben si mañana habrá comida en casa.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <Link href="/donar"
                        class="btn-pop inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-coral-500 to-coral-600 px-8 py-3.5 text-base font-bold text-white shadow-xl shadow-coral-900/20 hover:shadow-2xl transition">
                        ❤️ Quiero ayudar
                    </Link>
                    <a href="#como-ayudar"
                        class="btn-pop inline-flex items-center gap-2 rounded-full border-2 border-teal-800/60 px-7 py-3.5 text-base font-bold text-teal-900 hover:bg-teal-900/5 transition">
                        Cómo ayudar →
                    </a>
                </div>
            </div>
            <!-- Foto de la familia con desvanecido superior y onda navy inferior -->
            <div class="relative mt-10 sm:mt-12">
                <img src="/hero-familia.jpg" alt="Una madre y sus hijos haciendo su despensa" class="w-full max-h-[560px] object-cover object-[center_30%]" />
                <div class="pointer-events-none absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-[#faf6ee] via-[#faf6ee]/70 to-transparent"></div>
                <div class="pointer-events-none absolute inset-x-0 bottom-0 leading-none">
                    <svg viewBox="0 0 1440 130" preserveAspectRatio="none" class="w-full h-16 sm:h-24">
                        <path d="M0,70 C340,130 1080,10 1440,70 L1440,130 L0,130 Z" fill="#1c2742"></path>
                        <path d="M0,70 C340,130 1080,10 1440,70" fill="none" stroke="#c19a54" stroke-width="5"></path>
                    </svg>
                </div>
            </div>
        </section>

        <!-- STATS -->
        <section class="bg-white border-b border-slate-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 -mt-10 sm:-mt-14 relative z-10">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    <div v-for="s in statsDisplay" :key="s.label"
                        class="text-center bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <p class="text-3xl sm:text-4xl font-bold text-teal-800 font-serif">{{ s.value }}</p>
                        <p class="text-sm text-slate-500 mt-1.5">{{ s.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ROSH HASHANÁ -->
        <section id="rosh-hashana" class="scroll-mt-24 relative overflow-hidden bg-gradient-to-br from-teal-900 via-teal-900 to-teal-800 text-white">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-emerald-500/15 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-coral-500/10 blur-3xl"></div>
            </div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <!-- Foto -->
                    <div class="relative">
                        <div class="absolute -inset-2 rounded-[1.9rem] bg-gradient-to-br from-emerald-400/40 to-coral-400/20 blur-lg"></div>
                        <div class="relative overflow-hidden rounded-3xl ring-2 ring-emerald-300/40 shadow-2xl">
                            <img src="/rosh-hashana.jpg" alt="Familia celebrando Rosh Hashaná" class="w-full object-cover" />
                        </div>
                        <div class="absolute -bottom-4 right-4 rounded-2xl bg-white px-4 py-2 shadow-xl">
                            <span class="text-xl font-bold text-teal-800" dir="rtl">שנה טובה ומתוקה</span>
                        </div>
                    </div>
                    <!-- Copy -->
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-400/15 border border-emerald-300/40 px-4 py-1.5 text-sm font-semibold text-emerald-200 mb-6">
                            🍎🍯 Rosh Hashaná 5787
                        </span>
                        <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
                            Que su año nuevo también sea dulce
                        </h2>
                        <p class="text-lg text-teal-50/90 leading-relaxed mb-4">
                            Mientras muchas familias se sientan a la mesa con manzana y miel, hay viudas y huérfanos de la comunidad que no saben si tendrán qué servir.
                        </p>
                        <p class="text-base text-teal-100/80 leading-relaxed mb-8">
                            Antes de que suene el shofar, endulcemos su año: con tu donativo llevamos despensa y alegría a sus hogares para recibir Rosh Hashaná con dignidad. Tu tzedaká es un mérito para todo el año.
                        </p>
                        <div class="flex flex-wrap items-center gap-4">
                            <Link href="/donar?causa=6"
                                class="btn-pop inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-coral-500 to-coral-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-coral-900/30 hover:shadow-2xl transition">
                                ❤️ Donar para Rosh Hashaná
                            </Link>
                            <span class="text-sm text-teal-200/70">Recíbelos con una mesa digna 🍯</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ¿CÓMO AYUDAMOS? -->
        <section class="relative bg-white py-20 overflow-hidden">
            <div class="pointer-events-none absolute -top-10 right-0 h-64 w-64 rounded-full bg-coral-100/60 blur-3xl"></div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="font-accent text-2xl text-coral-600">Con dignidad y tranquilidad</span>
                    <h2 class="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-800">¿Cómo ayudamos?</h2>
                    <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Acompañamos a viudas y a sus hijos para que ninguna familia de la comunidad enfrente sola sus necesidades básicas.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="item in comoAyudamos" :key="item.titulo"
                        class="rounded-2xl border border-[#eaddc4] bg-[#faf6ee] p-7 transition hover:border-teal-300 hover:shadow-md">
                        <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.path" />
                            </svg>
                        </span>
                        <h3 class="text-base font-bold text-slate-800 mb-2">{{ item.titulo }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ item.texto }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ELIGE CÓMO AYUDAR (MONTOS) -->
        <section id="como-ayudar" class="scroll-mt-24 bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="font-accent text-2xl text-teal-600">Cada aportación es una mitzvá</span>
                    <h2 class="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-800">Elige cómo ayudar</h2>
                    <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Con tarjetas para despensa, cada familia compra su alimento en tiendas kosher con dignidad. Tú eliges el monto:</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                    <Link v-for="m in montos" :key="m.monto" :href="`/donar?monto=${m.monto}`"
                        :class="m.destacado ? 'border-coral-300 ring-1 ring-coral-200' : 'border-slate-200'"
                        class="group relative flex flex-col rounded-2xl border bg-white p-6 text-center transition hover:border-coral-300 hover:shadow-md">
                        <span v-if="m.destacado" class="absolute -top-2.5 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-coral-600 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white">Más elegido</span>
                        <span class="font-serif text-3xl font-bold text-teal-800">{{ fmt(m.monto) }}</span>
                        <span class="mx-auto mt-2 h-px w-8 bg-coral-300"></span>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">{{ m.texto }}</p>
                        <span class="mt-5 inline-flex items-center justify-center rounded-lg border border-teal-600 py-2 text-sm font-semibold text-teal-700 transition group-hover:bg-teal-700 group-hover:text-white">Donar</span>
                    </Link>
                </div>
                <p class="mt-8 text-center text-sm text-slate-500">¿Prefieres otro monto? <Link href="/donar" class="font-semibold text-coral-700 hover:text-coral-800 underline underline-offset-2">Dona la cantidad que tú quieras →</Link></p>
            </div>
        </section>

        <!-- TESTIMONIOS -->
        <section class="relative bg-slate-50 py-20 overflow-hidden">
            <div class="pointer-events-none absolute top-10 left-1/2 -translate-x-1/2 h-72 w-72 rounded-full bg-emerald-100/50 blur-3xl"></div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="font-accent text-2xl text-coral-600">Voces que inspiran</span>
                    <h2 class="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-800">{{ t('home.testimonials.title') }}</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="t_ in displayTestimonios" :key="t_.nombre"
                        class="card-lift bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:border-emerald-200 relative">
                        <p class="font-serif text-5xl text-emerald-300 leading-none mb-2">"</p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-5 -mt-4">{{ t_.texto ?? t_.contenido }}</p>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-coral-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ (t_.nombre ?? t_.nombre_donador ?? '?')[0] }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ t_.nombre ?? t_.nombre_donador }}</p>
                                <p class="text-xs text-coral-600 font-semibold">Plan {{ t_.plan ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CONFIANZA INSTITUCIONAL -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800">{{ t('home.trust.title') }}</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="n in [1,2,3,4]" :key="n"
                        class="card-lift text-center p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-coral-200">
                        <div :class="['from-coral-500 to-coral-600', 'from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-500', 'from-teal-600 to-coral-500'][n-1]"
                            class="h-14 w-14 rounded-2xl bg-gradient-to-br flex items-center justify-center text-2xl mx-auto mb-4 shadow-lg">
                            {{ ['📊','🎯','🔒','📄'][n-1] }}
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">{{ t(`home.trust.${n}.title`) }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ t(`home.trust.${n}.text`) }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA FINAL -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-coral-700 py-20">
            <div class="pointer-events-none absolute -top-10 -left-10 h-64 w-64 rounded-full bg-coral-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-16 -right-10 h-64 w-64 rounded-full bg-emerald-300/20 blur-3xl"></div>
            <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center text-white">
                <span class="text-4xl block mb-4 animate-heartbeat">❤️</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-extrabold mb-4">{{ t('home.cta.title') }}</h2>
                <p class="text-white/80 text-lg mb-8">{{ t('home.cta.subtitle') }}</p>
                <Link href="/donar"
                    class="btn-pop inline-flex items-center gap-2 rounded-2xl bg-white px-8 py-4 text-base font-bold text-coral-700 shadow-xl hover:bg-coral-50 transition">
                    ❤️ {{ t('home.cta.btn') }}
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
