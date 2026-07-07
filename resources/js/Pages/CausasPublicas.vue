<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({ causas: Array, categorias: Array })
const { t } = useI18n()

const search = ref('')
const catActiva = ref('todas')

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const pct = (r, m) => m > 0 ? Math.min(100, Math.round((r / m) * 100)) : 0

const staticCausas = [
    { id: 1, titulo: 'Alimentación Infantil', descripcion_corta: 'Garantizamos tres comidas diarias a niños en situación de vulnerabilidad en comunidades rurales y semiurbanas.', descripcion: '', meta_recaudacion: 200000, recaudado: 142000, categoria: 'alimentación', beneficiarios: 850, ubicacion: 'Oaxaca, México', activa: true },
    { id: 2, titulo: 'Educación para Todos', descripcion_corta: 'Becas, útiles y apoyo educativo para jóvenes de escasos recursos que sueñan con un mejor futuro.', descripcion: '', meta_recaudacion: 350000, recaudado: 210000, categoria: 'educación', beneficiarios: 320, ubicacion: 'Guerrero, México', activa: true },
    { id: 3, titulo: 'Salud Comunitaria', descripcion_corta: 'Brigadas médicas y medicamentos gratuitos para comunidades sin acceso a servicios de salud básicos.', descripcion: '', meta_recaudacion: 500000, recaudado: 389000, categoria: 'salud', beneficiarios: 1200, ubicacion: 'Chiapas, México', activa: true },
    { id: 4, titulo: 'Vivienda Digna', descripcion_corta: 'Mejoramiento de viviendas en zonas de riesgo, garantizando seguridad y dignidad a familias vulnerables.', descripcion: '', meta_recaudacion: 400000, recaudado: 95000, categoria: 'vivienda', beneficiarios: 180, ubicacion: 'Estado de México', activa: true },
    { id: 5, titulo: 'Emprendimiento Femenino', descripcion_corta: 'Capacitación y micro-créditos para mujeres jefas de familia que buscan independencia económica.', descripcion: '', meta_recaudacion: 250000, recaudado: 178000, categoria: 'emprendimiento', beneficiarios: 95, ubicacion: 'CDMX, México', activa: true },
    { id: 6, titulo: 'Adultos Mayores', descripcion_corta: 'Acompañamiento, atención médica y actividades recreativas para adultos mayores en situación de abandono.', descripcion: '', meta_recaudacion: 180000, recaudado: 167000, categoria: 'adultos mayores', beneficiarios: 230, ubicacion: 'Jalisco, México', activa: true },
]

const displayCausas = computed(() => props.causas?.length ? props.causas : staticCausas)

const categorias = computed(() => {
    const cats = [...new Set(displayCausas.value.map(c => c.categoria))]
    return cats
})

const filtradas = computed(() => {
    return displayCausas.value.filter(c => {
        const matchSearch = !search.value || c.titulo.toLowerCase().includes(search.value.toLowerCase()) || c.descripcion_corta?.toLowerCase().includes(search.value.toLowerCase())
        const matchCat = catActiva.value === 'todas' || c.categoria === catActiva.value
        return matchSearch && matchCat
    })
})
</script>

<template>
    <Head title="Causas y Programas — AJDUT México" />
    <PublicLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-coral-700 text-white py-16">
            <div class="pointer-events-none absolute -top-16 -right-10 h-64 w-64 rounded-full bg-coral-400/20 blur-3xl animate-float-slow"></div>
            <div class="pointer-events-none absolute -bottom-10 -left-10 h-56 w-56 rounded-full bg-emerald-300/20 blur-3xl animate-float-slow" style="animation-delay:2s"></div>
            <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <span class="font-accent text-2xl text-coral-200">Causas activas</span>
                <h1 class="font-serif text-4xl sm:text-5xl font-extrabold mt-1 mb-4">{{ t('causes.title') }}</h1>
                <p class="text-xl text-white/85 max-w-2xl mx-auto">{{ t('causes.subtitle') }}</p>
            </div>
        </section>

        <!-- Filtros -->
        <section class="bg-white border-b border-slate-100 sticky top-[85px] z-30">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center gap-3">
                <input v-model="search" type="text"
                    :placeholder="t('causes.search')"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:border-coral-400 flex-1 min-w-48" />
                <button @click="catActiva = 'todas'"
                    :class="catActiva === 'todas' ? 'bg-coral-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition capitalize whitespace-nowrap">
                    {{ t('causes.all') }}
                </button>
                <button v-for="cat in categorias" :key="cat" @click="catActiva = cat"
                    :class="catActiva === cat ? 'bg-coral-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition capitalize whitespace-nowrap">
                    {{ cat }}
                </button>
            </div>
        </section>

        <!-- Listado -->
        <section class="py-16 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="filtradas.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="(causa, i) in filtradas" :key="causa.id"
                        class="card-lift bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:border-coral-200 transition group">
                        <!-- Color bar by category -->
                        <div :class="['from-coral-500 to-coral-600', 'from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-600'][i % 3]" class="h-2 bg-gradient-to-r"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <span :class="i % 3 === 0 ? 'bg-coral-50 text-coral-700' : i % 3 === 1 ? 'bg-teal-50 text-teal-700' : 'bg-emerald-50 text-emerald-700'"
                                    class="inline-block rounded-full text-xs font-bold px-3 py-1 capitalize">{{ causa.categoria }}</span>
                                <span v-if="causa.ubicacion" class="text-xs text-slate-400">📍 {{ causa.ubicacion }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-coral-700 transition">{{ causa.titulo }}</h3>
                            <p class="text-sm text-slate-500 mb-5 leading-relaxed">{{ causa.descripcion_corta }}</p>

                            <!-- Progress -->
                            <div class="mb-1 flex justify-between text-xs font-semibold">
                                <span class="text-coral-600 font-bold text-base">{{ pct(causa.recaudado, causa.meta_recaudacion) }}%</span>
                                <span class="text-slate-400">{{ t('causes.goal') }}: {{ fmt(causa.meta_recaudacion) }}</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-100 overflow-hidden mb-2">
                                <div :class="['from-coral-500 to-coral-600', 'from-teal-500 to-teal-700', 'from-emerald-400 to-emerald-600'][i % 3]" class="h-3 rounded-full bg-gradient-to-r transition-all duration-700"
                                    :style="{ width: pct(causa.recaudado, causa.meta_recaudacion) + '%' }"></div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-400 mb-5">
                                <span>{{ fmt(causa.recaudado) }} {{ t('causes.raised') }}</span>
                                <span v-if="causa.beneficiarios">{{ causa.beneficiarios }} {{ t('causes.beneficiaries') }}</span>
                            </div>
                            <Link :href="causa.id ? `/donar?causa=${causa.id}` : '/donar'"
                                class="btn-pop flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-coral-500 to-coral-600 py-2.5 text-sm font-bold text-white shadow-md shadow-coral-500/20 hover:shadow-lg transition">
                                ❤️ {{ t('causes.donate') }}
                            </Link>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-16 text-slate-400">
                    <p class="text-5xl mb-4">🔍</p>
                    <p class="text-lg font-semibold">No se encontraron causas con ese filtro.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
