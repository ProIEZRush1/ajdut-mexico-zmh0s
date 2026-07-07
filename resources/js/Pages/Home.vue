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

const statsDisplay = computed(() => [
    { value: props.stats?.donadores ?? '0', label: t('home.stats.donors'), icon: '👥' },
    { value: fmt(props.stats?.total_recaudado ?? 0), label: t('home.stats.raised'), icon: '💰' },
    { value: props.stats?.causas_activas ?? '0', label: t('home.stats.causes'), icon: '❤️' },
    { value: props.stats?.beneficiarios ?? '5,000+', label: t('home.stats.beneficiaries'), icon: '🤝' },
])

const staticTestimonios = [
    { nombre: 'María González', texto: 'Donar a AJDUT México me cambió la perspectiva. Veo cómo mi aportación mensual ayuda a familias reales. Los reportes de transparencia me dan total confianza.', plan: 'Padrino' },
    { nombre: 'Carlos Ramírez', texto: 'La plataforma es increíblemente fácil de usar. En minutos configuré mi donación mensual y recibo actualizaciones de impacto. ¡Muy recomendado!', plan: 'Amigo' },
    { nombre: 'Ana Martínez', texto: 'Como empresa, queríamos hacer una diferencia real. AJDUT México nos dio transparencia total sobre el destino de nuestros recursos.', plan: 'Benefactor' },
]

const displayTestimonios = computed(() => props.testimonios?.length ? props.testimonios : staticTestimonios)

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
                            <span class="animate-heartbeat inline-block">❤️</span> {{ t('home.eyebrow') }}
                        </span>
                        <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-7 tracking-tight">
                            {{ t('home.hero.title') }}
                        </h1>
                        <p class="text-lg sm:text-xl text-white/85 mb-10 max-w-lg leading-relaxed">
                            {{ t('home.hero.subtitle') }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <Link href="/donar"
                                class="btn-pop inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-coral-500 to-coral-600 px-7 py-3.5 text-base font-bold text-white shadow-xl shadow-coral-900/30 hover:shadow-2xl transition">
                                ❤️ {{ t('home.hero.cta') }}
                            </Link>
                            <Link href="/causas-publicas"
                                class="btn-pop inline-flex items-center gap-2 rounded-2xl border-2 border-white/40 px-7 py-3.5 text-base font-bold text-white hover:bg-white/10 transition">
                                {{ t('home.hero.cta2') }} →
                            </Link>
                        </div>
                    </div>
                    <div class="hidden lg:flex justify-center">
                        <div class="relative">
                            <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-coral-400/30 via-emerald-300/20 to-transparent blur-xl"></div>
                            <div class="relative h-72 w-72 xl:h-80 xl:w-80 rounded-3xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center shadow-2xl">
                                <img src="/brand-logo.jpeg" alt="AJDUT México" class="h-48 w-48 object-contain rounded-2xl" />
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

        <!-- CAUSAS ACTIVAS -->
        <section class="relative bg-slate-50 py-20 overflow-hidden">
            <div class="pointer-events-none absolute -top-10 right-0 h-64 w-64 rounded-full bg-coral-100/60 blur-3xl"></div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="font-accent text-2xl text-coral-600">Programas con corazón</span>
                    <h2 class="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-800">{{ t('home.causes.title') }}</h2>
                    <p class="mt-3 text-slate-500 max-w-2xl mx-auto">{{ t('home.causes.subtitle') }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div v-for="(causa, i) in displayCausas" :key="causa.id ?? causa.titulo"
                        class="card-lift bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:border-coral-200 transition group">
                        <div :class="causaAccents[i % 3]" class="h-3 bg-gradient-to-r"></div>
                        <div class="p-6">
                            <span :class="i % 3 === 0 ? 'bg-coral-50 text-coral-700' : i % 3 === 1 ? 'bg-teal-50 text-teal-700' : 'bg-emerald-50 text-emerald-700'"
                                class="inline-block rounded-full text-xs font-bold px-3 py-1 mb-3 capitalize">{{ causa.categoria }}</span>
                            <h3 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-coral-700 transition">{{ causa.titulo }}</h3>
                            <p class="text-sm text-slate-500 mb-5 leading-relaxed">{{ causa.descripcion_corta }}</p>
                            <!-- Progress bar -->
                            <div class="mb-1 flex justify-between text-xs font-semibold">
                                <span class="text-coral-600">{{ pct(causa.recaudado, causa.meta_recaudacion) }}%</span>
                                <span class="text-slate-400">{{ t('home.causes.goal') }}: {{ fmt(causa.meta_recaudacion) }}</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden mb-2">
                                <div :class="causaAccents[i % 3]" class="h-2.5 rounded-full bg-gradient-to-r transition-all duration-700"
                                    :style="{ width: pct(causa.recaudado, causa.meta_recaudacion) + '%' }"></div>
                            </div>
                            <p class="text-xs text-slate-400 mb-5">{{ fmt(causa.recaudado) }} {{ t('home.causes.raised') }}</p>
                            <Link :href="causa.id ? `/donar?causa=${causa.id}` : '/donar'"
                                class="btn-pop flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-coral-500 to-coral-600 py-2.5 text-sm font-bold text-white shadow-md shadow-coral-500/20 hover:shadow-lg transition">
                                ❤️ {{ t('home.causes.donate') }}
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <Link href="/causas-publicas"
                        class="btn-pop inline-flex items-center gap-2 rounded-xl border-2 border-teal-600 px-6 py-3 text-sm font-bold text-teal-600 hover:bg-teal-50 transition">
                        {{ t('home.causes.viewall') }} →
                    </Link>
                </div>
            </div>
        </section>

        <!-- PLANES DE DONACIÓN -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="font-accent text-2xl text-teal-600">Únete a la comunidad</span>
                    <h2 class="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-800">{{ t('home.plans.title') }}</h2>
                    <p class="mt-3 text-slate-500 max-w-2xl mx-auto">{{ t('home.plans.subtitle') }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="(plan, idx) in displayPlanes" :key="plan.id ?? plan.nombre"
                        :class="plan.destacado ? 'ring-2 ring-coral-500 scale-105 shadow-xl' : 'shadow-sm'"
                        class="card-lift relative rounded-2xl border border-slate-200 overflow-hidden bg-white hover:shadow-2xl transition">
                        <div v-if="plan.destacado" class="bg-coral-600 text-white text-xs font-bold text-center py-1.5 uppercase tracking-wider">
                            ⭐ {{ t('plans.recommended') }}
                        </div>
                        <div :class="`bg-gradient-to-r ${planColors[idx % 3]}`" class="relative overflow-hidden p-6 text-white">
                            <span class="absolute -right-3 -top-3 text-6xl opacity-20">{{ planIcons[idx % 3] }}</span>
                            <span class="text-3xl block mb-2">{{ planIcons[idx % 3] }}</span>
                            <h3 class="text-xl font-extrabold">{{ plan.nombre }}</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-3xl font-extrabold">{{ fmt(plan.monto_sugerido) }}</span>
                                <span class="text-white/80 text-sm">/{{ plan.frecuencia === 'mensual' ? 'mes' : plan.frecuencia === 'anual' ? 'año' : 'vez' }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-slate-500 mb-5 leading-relaxed">{{ plan.descripcion }}</p>
                            <ul v-if="Array.isArray(plan.beneficios)" class="space-y-2 mb-6">
                                <li v-for="b in plan.beneficios" :key="b" class="flex items-start gap-2 text-sm">
                                    <span class="text-coral-500 mt-0.5 flex-shrink-0">✓</span>
                                    <span class="text-slate-600">{{ b }}</span>
                                </li>
                            </ul>
                            <Link :href="plan.id ? `/donar?plan=${plan.id}` : '/donar'"
                                :class="plan.destacado ? 'bg-coral-600 text-white hover:bg-coral-700 shadow-md shadow-coral-500/30' : 'border border-teal-600 text-teal-600 hover:bg-teal-50'"
                                class="btn-pop block text-center rounded-xl py-2.5 text-sm font-bold transition">
                                {{ t('home.plans.join') }}
                            </Link>
                        </div>
                    </div>
                </div>
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
