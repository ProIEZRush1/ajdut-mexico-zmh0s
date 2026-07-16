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
    { icon: '💳', titulo: 'Tarjetas para despensa', texto: 'Brindamos tarjetas con saldo para que cada familia pueda comprar alimentos en tiendas kosher con dignidad y tranquilidad.' },
    { icon: '🤝', titulo: 'Apoyo a familias', texto: 'Acompañamos a viudas y a sus hijos en momentos difíciles, asegurando que no enfrenten solos sus necesidades básicas.' },
    { icon: '🫂', titulo: 'Juntos hacemos más', texto: 'Cada aportación nos permite llegar a más hogares y brindar estabilidad a quienes más lo necesitan.' },
    { icon: '✨', titulo: 'Sé parte del cambio', texto: 'Tu ayuda devuelve esperanza y tranquilidad a familias que están reconstruyendo su vida.' },
]

const montos = [
    { monto: 200, texto: 'Ayudas a una familia con alimentos básicos.', destacado: false },
    { monto: 500, texto: 'Brindas apoyo para la despensa de una familia.', destacado: false },
    { monto: 1000, texto: 'Das tranquilidad y alimento a una familia.', destacado: true },
    { monto: 2000, texto: 'Cubres una tarjeta mensual completa para una familia.', destacado: false },
    { monto: 4000, texto: 'Apoyas a 2 familias completas durante el mes.', destacado: false },
]
const montoColors = ['from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-600', 'from-coral-500 to-coral-600', 'from-teal-600 to-coral-500', 'from-coral-500 to-teal-700']

const statsDisplay = computed(() => [
    { value: props.stats?.donadores ?? '0', label: t('home.stats.donors'), icon: '👥' },
    { value: fmt(props.stats?.total_recaudado ?? 0), label: t('home.stats.raised'), icon: '💰' },
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
    <Head title="AJDUT México — Plataforma de Donaciones" />
    <PublicLayout>
        <!-- HERO -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-teal-600 text-white">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-coral-400/20 blur-3xl animate-float-slow"></div>
                <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-emerald-300/20 blur-3xl animate-float-slow" style="animation-delay:2s"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-teal-500/10 blur-3xl"></div>
                <div class="absolute inset-0 opacity-[0.06] text-white bg-dot-pattern"></div>
                <span class="absolute top-16 right-[12%] text-4xl opacity-30 animate-float">✨</span>
                <span class="absolute bottom-24 left-[8%] text-5xl opacity-20 animate-float" style="animation-delay:1.2s">🤲</span>
            </div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-coral-500/20 border border-coral-300/40 px-4 py-1.5 text-sm font-semibold text-coral-100 backdrop-blur mb-7 tracking-wide shadow-lg shadow-coral-900/10">
                            <span class="animate-heartbeat inline-block">❤️</span> Asóciate a esta gran mitzvá
                        </span>
                        <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-7 tracking-tight">
                            Alimento y esperanza para nuestras familias
                        </h1>
                        <p class="text-lg sm:text-xl text-white/85 mb-10 max-w-lg leading-relaxed">
                            Hay viudas que sonríen frente a sus hijos… mientras por dentro no saben si mañana habrá comida en casa. Tu ayuda les devuelve tranquilidad y alimento con dignidad.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <Link href="/donar"
                                class="btn-pop inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-coral-500 to-coral-600 px-7 py-3.5 text-base font-bold text-white shadow-xl shadow-coral-900/30 hover:shadow-2xl transition">
                                ❤️ Quiero ayudar
                            </Link>
                            <a href="#como-ayudar"
                                class="btn-pop inline-flex items-center gap-2 rounded-2xl border-2 border-white/40 px-7 py-3.5 text-base font-bold text-white hover:bg-white/10 transition">
                                Cómo ayudar →
                            </a>
                        </div>
                    </div>
                    <div class="hidden lg:flex justify-center">
                        <div class="relative">
                            <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-coral-400/30 via-emerald-300/20 to-transparent blur-xl"></div>
                            <div class="relative h-72 w-72 xl:h-80 xl:w-80 rounded-3xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center shadow-2xl">
                                <img src="/brand-logo.svg" alt="AJDUT México" class="h-48 w-48 object-contain rounded-2xl" />
                            </div>
                            <div class="absolute -bottom-6 -left-6 rounded-2xl bg-white p-4 shadow-xl animate-float">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Impacto Real</p>
                                <p class="text-2xl font-extrabold text-coral-600">+5,000</p>
                                <p class="text-xs text-slate-500">beneficiarios</p>
                            </div>
                            <div class="absolute -top-4 -right-4 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-500 p-3 shadow-xl animate-float" style="animation-delay:1.5s">
                                <p class="text-2xl">🏆</p>
                                <p class="text-xs font-bold text-emerald-950">Confiables</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATS -->
        <section class="bg-white border-b border-slate-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 -mt-10 sm:-mt-14 relative z-10">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    <div v-for="(s, i) in statsDisplay" :key="s.label"
                        class="card-lift text-center bg-white rounded-2xl border border-slate-100 shadow-lg shadow-slate-900/5 p-6 hover:border-coral-200 hover:shadow-xl">
                        <span :class="['from-coral-500 to-coral-600', 'from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-500', 'from-coral-400 to-teal-600'][i % 4]"
                            class="h-12 w-12 rounded-2xl bg-gradient-to-br flex items-center justify-center text-2xl mx-auto mb-3 shadow-md">{{ s.icon }}</span>
                        <p class="text-2xl sm:text-3xl font-extrabold text-slate-800 font-serif">{{ s.value }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ s.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ¿CÓMO AYUDAMOS? -->
        <section class="relative bg-slate-50 py-20 overflow-hidden">
            <div class="pointer-events-none absolute -top-10 right-0 h-64 w-64 rounded-full bg-coral-100/60 blur-3xl"></div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="font-accent text-2xl text-coral-600">Con dignidad y tranquilidad</span>
                    <h2 class="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-800">¿Cómo ayudamos?</h2>
                    <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Acompañamos a viudas y a sus hijos para que ninguna familia de la comunidad enfrente sola sus necesidades básicas.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="(item, i) in comoAyudamos" :key="item.titulo"
                        class="card-lift bg-white rounded-2xl border border-slate-200 p-7 shadow-sm hover:shadow-2xl hover:border-coral-200 transition">
                        <span :class="['from-coral-500 to-coral-600', 'from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-500', 'from-coral-400 to-teal-600'][i % 4]"
                            class="h-14 w-14 rounded-2xl bg-gradient-to-br flex items-center justify-center text-3xl mb-4 shadow-md">{{ item.icon }}</span>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ item.titulo }}</h3>
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
                    <Link v-for="(m, idx) in montos" :key="m.monto" :href="`/donar?monto=${m.monto}`"
                        :class="m.destacado ? 'ring-2 ring-coral-500 lg:scale-105 shadow-xl' : 'shadow-sm hover:border-coral-200'"
                        class="card-lift group relative flex flex-col rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-2xl transition">
                        <div v-if="m.destacado" class="bg-coral-600 text-white text-[11px] font-bold text-center py-1 uppercase tracking-wider">⭐ Más elegido</div>
                        <div :class="`bg-gradient-to-br ${montoColors[idx % montoColors.length]}`" class="p-5 text-white text-center">
                            <span class="block text-3xl font-extrabold font-serif">{{ fmt(m.monto) }}</span>
                        </div>
                        <div class="flex flex-1 flex-col p-5 text-center">
                            <p class="flex-1 text-sm text-slate-600 leading-relaxed">{{ m.texto }}</p>
                            <span class="btn-pop mt-4 inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-coral-500 to-coral-600 py-2.5 text-sm font-bold text-white shadow-md shadow-coral-500/20 group-hover:shadow-lg transition">
                                ❤️ Donar
                            </span>
                        </div>
                    </Link>
                </div>
                <p class="mt-8 text-center text-sm text-slate-500">¿Prefieres otro monto? <Link href="/donar" class="font-semibold text-coral-600 hover:text-coral-700">Dona la cantidad que tú quieras →</Link></p>
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
