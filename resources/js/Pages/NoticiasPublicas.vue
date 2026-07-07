<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({ noticias: Array, categorias: Array })
const { t } = useI18n()

const search = ref('')
const catActiva = ref('todas')

const staticNoticias = [
    { id: 1, titulo: 'Inauguramos escuela en comunidad de Oaxaca', resumen: 'Gracias a sus donaciones, hoy 120 niños tienen acceso a educación de calidad en una nueva aula construida con fondos recaudados en 2025.', categoria: { nombre: 'Educación', color: '#0d9488' }, autor: 'Equipo AJDUT', fecha_publicacion: '2026-06-15', publicada: true },
    { id: 2, titulo: '5,000 familias beneficiadas: nuestro mayor logro', resumen: 'Este año alcanzamos la cifra de 5,000 familias apoyadas a través de nuestros programas. Un hito histórico que es posible gracias a cada donador.', categoria: { nombre: 'Impacto', color: '#f59e0b' }, autor: 'Patricia Morales', fecha_publicacion: '2026-06-01', publicada: true },
    { id: 3, titulo: 'Brigada médica atiende a 800 personas en Chiapas', resumen: 'Nuestro programa de Salud Comunitaria organizó tres días de consultas médicas gratuitas en comunidades sin acceso a servicios de salud.', categoria: { nombre: 'Salud', color: '#8b5cf6' }, autor: 'Dr. Alejandro Ríos', fecha_publicacion: '2026-05-20', publicada: true },
    { id: 4, titulo: 'Nuevos planes de donación disponibles desde julio', resumen: 'Lanzamos planes de donación renovados con mejores beneficios y opciones más flexibles para que más personas puedan contribuir.', categoria: { nombre: 'Noticias', color: '#ef4444' }, autor: 'Equipo AJDUT', fecha_publicacion: '2026-05-10', publicada: true },
    { id: 5, titulo: 'Alianza con empresas para ampliar impacto', resumen: 'Firmamos convenios de colaboración con cinco empresas comprometidas con la responsabilidad social. Juntos llegaremos más lejos.', categoria: { nombre: 'Institucional', color: '#10b981' }, autor: 'Equipo AJDUT', fecha_publicacion: '2026-04-28', publicada: true },
    { id: 6, titulo: 'Informe Q1 2026: transparencia en números', resumen: 'Publicamos nuestro reporte trimestral con detalles de cada peso recaudado y gastado. La confianza de nuestros donadores es nuestra prioridad.', categoria: { nombre: 'Transparencia', color: '#6366f1' }, autor: 'Sofía Vega', fecha_publicacion: '2026-04-15', publicada: true },
]

const displayNoticias = computed(() => props.noticias?.filter(n => n.publicada)?.length ? props.noticias.filter(n => n.publicada) : staticNoticias)

const categoriasDisplay = computed(() => {
    const cats = [...new Set(displayNoticias.value.map(n => n.categoria?.nombre).filter(Boolean))]
    return cats
})

const filtradas = computed(() => {
    return displayNoticias.value.filter(n => {
        const matchSearch = !search.value || n.titulo.toLowerCase().includes(search.value.toLowerCase()) || n.resumen?.toLowerCase().includes(search.value.toLowerCase())
        const matchCat = catActiva.value === 'todas' || n.categoria?.nombre === catActiva.value
        return matchSearch && matchCat
    })
})

const fmtDate = (d) => {
    if (!d) return ''
    return new Date(d).toLocaleDateString('es-MX', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>

<template>
    <Head title="Noticias y Blog — AJDUT México" />
    <PublicLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-coral-700 text-white py-16">
            <div class="pointer-events-none absolute -top-16 -right-10 h-64 w-64 rounded-full bg-coral-400/20 blur-3xl animate-float-slow"></div>
            <div class="pointer-events-none absolute -bottom-10 -left-10 h-56 w-56 rounded-full bg-emerald-300/20 blur-3xl animate-float-slow" style="animation-delay:2s"></div>
            <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <span class="font-accent text-2xl text-coral-200">Historias que inspiran</span>
                <h1 class="font-serif text-4xl sm:text-5xl font-extrabold mt-1 mb-4">{{ t('news.title') }}</h1>
                <p class="text-xl text-white/85 max-w-2xl mx-auto">{{ t('news.subtitle') }}</p>
            </div>
        </section>

        <!-- Filtros -->
        <section class="bg-white border-b border-slate-100 sticky top-[85px] z-30">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center gap-3">
                <input v-model="search" type="text" :placeholder="t('news.search')"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:border-coral-400 flex-1 min-w-48" />
                <button @click="catActiva = 'todas'"
                    :class="catActiva === 'todas' ? 'bg-coral-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition whitespace-nowrap">
                    {{ t('news.all') }}
                </button>
                <button v-for="cat in categoriasDisplay" :key="cat" @click="catActiva = cat"
                    :class="catActiva === cat ? 'bg-coral-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition whitespace-nowrap">
                    {{ cat }}
                </button>
            </div>
        </section>

        <!-- Noticias grid -->
        <section class="py-16 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="filtradas.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <article v-for="n in filtradas" :key="n.id"
                        class="card-lift bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl transition group">
                        <!-- Color header by category -->
                        <div class="h-2" :style="{ background: n.categoria?.color ?? '#284a87' }"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-block rounded-full text-white text-xs font-bold px-3 py-1"
                                    :style="{ background: n.categoria?.color ?? '#284a87' }">
                                    {{ n.categoria?.nombre ?? 'Blog' }}
                                </span>
                                <span class="text-xs text-slate-400">{{ fmtDate(n.fecha_publicacion) }}</span>
                            </div>
                            <h3 class="font-bold text-slate-800 text-base mb-2 group-hover:text-coral-700 transition leading-snug">{{ n.titulo }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed mb-5">{{ n.resumen }}</p>
                            <div class="flex items-center justify-between">
                                <span v-if="n.autor" class="text-xs text-slate-400">✍️ {{ n.autor }}</span>
                                <button class="text-sm font-semibold text-coral-600 hover:text-coral-700 transition">{{ t('news.readmore') }} →</button>
                            </div>
                        </div>
                    </article>
                </div>
                <div v-else class="text-center py-16 text-slate-400">
                    <p class="text-5xl mb-4">📰</p>
                    <p class="text-lg font-semibold">No se encontraron artículos con ese filtro.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
