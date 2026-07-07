<script setup>
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({ reportes: Array, metricas: Object })
const { t } = useI18n()

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const fmtPct = (n) => Math.round(n ?? 0) + '%'

const staticReportes = [
    { id: 1, titulo: 'Informe Anual 2025', tipo: 'anual', anio: 2025, descripcion: 'Resumen completo de actividades, fondos recaudados y proyectos apoyados durante el año 2025.', total_recaudado: 1850000, total_gastado: 1720000, donadores_activos: 342, beneficiarios: 1840, publicado: true },
    { id: 2, titulo: 'Informe Anual 2024', tipo: 'anual', anio: 2024, descripcion: 'Resumen completo de actividades, fondos recaudados y proyectos apoyados durante el año 2024.', total_recaudado: 1420000, total_gastado: 1310000, donadores_activos: 275, beneficiarios: 1450, publicado: true },
    { id: 3, titulo: 'Informe Q1 2026', tipo: 'trimestral', anio: 2026, trimestre: 'Q1', descripcion: 'Primer trimestre 2026: reporte de actividades y uso de fondos enero-marzo.', total_recaudado: 520000, total_gastado: 480000, donadores_activos: 389, beneficiarios: 620, publicado: true },
]

const displayReportes = props.reportes?.filter(r => r.publicado)?.length ? props.reportes.filter(r => r.publicado) : staticReportes

const usoFondos = [
    { label: 'Programas y causas directas', pct: 82, color: 'bg-coral-500' },
    { label: 'Administración y operaciones', pct: 10, color: 'bg-emerald-400' },
    { label: 'Comunicación y captación', pct: 5, color: 'bg-teal-500' },
    { label: 'Reserva institucional', pct: 3, color: 'bg-slate-400' },
]

const metricasDisplay = [
    { label: 'Donadores activos', value: props.metricas?.donadores ?? '389', icon: '👥' },
    { label: 'Total recaudado 2025', value: fmt(props.metricas?.total_recaudado ?? 1850000), icon: '💰' },
    { label: 'Beneficiarios atendidos', value: props.metricas?.beneficiarios ?? '5,000+', icon: '🤝' },
    { label: 'Causas completadas', value: props.metricas?.causas_completadas ?? '18', icon: '✅' },
    { label: 'Eficiencia operativa', value: '92%', icon: '📈' },
    { label: 'Satisfacción donadores', value: '98%', icon: '⭐' },
]
</script>

<template>
    <Head title="Transparencia — AJDUT México" />
    <PublicLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-coral-700 text-white py-16">
            <div class="pointer-events-none absolute -top-16 -right-10 h-64 w-64 rounded-full bg-coral-400/20 blur-3xl animate-float-slow"></div>
            <div class="pointer-events-none absolute -bottom-10 -left-10 h-56 w-56 rounded-full bg-emerald-300/20 blur-3xl animate-float-slow" style="animation-delay:2s"></div>
            <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <span class="font-accent text-2xl text-coral-200">Cuentas claras</span>
                <h1 class="font-serif text-4xl sm:text-5xl font-extrabold mt-1 mb-4">{{ t('transparency.title') }}</h1>
                <p class="text-xl text-white/85 max-w-2xl mx-auto">{{ t('transparency.subtitle') }}</p>
            </div>
        </section>

        <!-- Métricas de impacto -->
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="font-serif text-2xl font-extrabold text-slate-800 text-center mb-10">{{ t('transparency.metrics.title') }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
                    <div v-for="(m, i) in metricasDisplay" :key="m.label"
                        class="card-lift bg-slate-50 rounded-2xl border border-slate-100 p-5 text-center hover:border-coral-200 hover:bg-white hover:shadow-lg transition">
                        <span :class="['bg-coral-100', 'bg-teal-100', 'bg-emerald-100', 'bg-coral-100', 'bg-teal-100', 'bg-emerald-100'][i % 6]"
                            class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3">{{ m.icon }}</span>
                        <p class="text-xl font-extrabold text-slate-800">{{ m.value }}</p>
                        <p class="text-xs text-slate-500 mt-1 leading-tight">{{ m.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Uso de fondos -->
        <section class="py-16 bg-slate-50">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-extrabold text-slate-800 text-center mb-10">{{ t('transparency.funds.title') }}</h2>
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                    <div class="space-y-5">
                        <div v-for="item in usoFondos" :key="item.label">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="font-semibold text-slate-700">{{ item.label }}</span>
                                <span class="font-bold text-slate-800">{{ item.pct }}%</span>
                            </div>
                            <div class="h-4 rounded-full bg-slate-100 overflow-hidden">
                                <div :class="item.color" class="h-4 rounded-full transition-all duration-700" :style="{ width: item.pct + '%' }"></div>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-6 text-center">
                        Datos correspondientes al ejercicio fiscal 2025. Auditado por despacho externo independiente.
                    </p>
                </div>
            </div>
        </section>

        <!-- Informes descargables -->
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-extrabold text-slate-800 text-center mb-10">{{ t('transparency.reports') }}</h2>
                <div class="space-y-4">
                    <div v-for="r in displayReportes" :key="r.id"
                        class="card-lift bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:border-coral-200 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-coral-500 to-teal-600 flex items-center justify-center text-2xl flex-shrink-0">📄</div>
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ r.titulo }}</h3>
                                    <p class="text-sm text-slate-500 mt-0.5">{{ r.descripcion }}</p>
                                    <div class="flex flex-wrap gap-4 mt-2 text-xs text-slate-400">
                                        <span>💰 Recaudado: <strong class="text-slate-600">{{ fmt(r.total_recaudado) }}</strong></span>
                                        <span>📊 Gastado: <strong class="text-slate-600">{{ fmt(r.total_gastado) }}</strong></span>
                                        <span>👥 Donadores: <strong class="text-slate-600">{{ r.donadores_activos }}</strong></span>
                                        <span>🤝 Beneficiarios: <strong class="text-slate-600">{{ r.beneficiarios }}</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span v-if="r.archivo"
                                    class="btn-pop inline-flex items-center gap-2 rounded-xl bg-coral-50 border border-coral-200 px-4 py-2 text-sm font-semibold text-coral-700 hover:bg-coral-100 transition cursor-pointer">
                                    ⬇️ {{ t('transparency.download') }}
                                </span>
                                <span v-else class="inline-flex items-center gap-2 rounded-xl bg-slate-50 border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-400">
                                    📋 Disponible pronto
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Compromiso -->
        <section class="relative overflow-hidden py-16 bg-gradient-to-br from-teal-800 via-teal-700 to-coral-700 text-white">
            <div class="pointer-events-none absolute -top-10 -left-10 h-56 w-56 rounded-full bg-coral-400/20 blur-3xl"></div>
            <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="font-serif text-3xl font-extrabold mb-4">Nuestro compromiso contigo</h2>
                <p class="text-white/85 text-lg mb-2">En AJDUT México, la transparencia no es una opción: es nuestra razón de ser. Cada donación se administra con la misma honestidad con la que fue entregada.</p>
                <p class="text-white/70 text-sm mt-6">¿Tienes dudas sobre el uso de fondos? Escríbenos y con gusto te respondemos.</p>
            </div>
        </section>
    </PublicLayout>
</template>
