<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({ planes: Array })
const { t } = useI18n()

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const freqLabel = (f) => ({ unica: t('plans.frequency.unica'), mensual: t('plans.frequency.mensual'), anual: t('plans.frequency.anual') })[f] ?? f

const staticPlanes = [
    {
        id: null, nombre: 'Amigo', monto_sugerido: 150, frecuencia: 'mensual', activo: true, destacado: false, orden: 1,
        descripcion: 'El primer paso para transformar vidas. Con tu aportación mensual apoyas programas de alimentación y educación básica.',
        beneficios: ['Newsletter mensual con historias de impacto', 'Reporte trimestral de uso de fondos', 'Certificado digital de donador', 'Recibo fiscal deducible'],
    },
    {
        id: null, nombre: 'Padrino', monto_sugerido: 500, frecuencia: 'mensual', activo: true, destacado: true, orden: 2,
        descripcion: 'Tu aportación mensual patrocina directamente a una familia o proyecto específico. Conoce su historia y avance.',
        beneficios: ['Todo lo incluido en plan Amigo', 'Historia de impacto personalizada de tu apadrinado', 'Invitación a eventos y presentaciones anuales', 'Acceso a portal del donador con historial completo', 'Reconocimiento en nuestro muro de padrinos'],
    },
    {
        id: null, nombre: 'Benefactor', monto_sugerido: 2000, frecuencia: 'mensual', activo: true, destacado: false, orden: 3,
        descripcion: 'Conviértete en pilar de nuestra institución. Tu aportación impulsa múltiples programas y recibes reconocimiento especial.',
        beneficios: ['Todo lo incluido en plan Padrino', 'Placa de reconocimiento en instalaciones físicas', 'Reunión anual con el Consejo Directivo', 'Visita guiada a proyectos apoyados', 'Mención en informes anuales públicos', 'Soporte prioritario del equipo AJDUT'],
    },
]

const displayPlanes = computed(() => props.planes?.length ? props.planes : staticPlanes)

const planColors = [
    { from: 'from-teal-500', to: 'to-emerald-500', shadow: 'shadow-teal-500/20' },
    { from: 'from-amber-500', to: 'to-orange-500', shadow: 'shadow-amber-500/20' },
    { from: 'from-violet-500', to: 'to-purple-600', shadow: 'shadow-violet-500/20' },
]
const planIcons = ['🌱', '🌟', '💎']

const montoLibre = ref('')
const frecuenciaLibre = ref('mensual')
</script>

<template>
    <Head title="Planes de Donación — AJDUT México" />
    <PublicLayout>
        <!-- Hero -->
        <section class="bg-gradient-to-br from-teal-700 to-emerald-600 text-white py-16">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">{{ t('plans.title') }}</h1>
                <p class="text-xl text-white/85 max-w-2xl mx-auto">{{ t('plans.subtitle') }}</p>
            </div>
        </section>

        <!-- Planes -->
        <section class="py-20 bg-slate-50">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                    <div v-for="(plan, idx) in displayPlanes" :key="plan.id ?? plan.nombre"
                        :class="plan.destacado ? 'md:scale-105 ring-2 ring-teal-500' : ''"
                        class="relative bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition flex flex-col">
                        <!-- Popular badge -->
                        <div v-if="plan.destacado" class="bg-teal-600 text-white text-xs font-bold text-center py-2 uppercase tracking-wider">
                            ⭐ {{ t('plans.recommended') }}
                        </div>
                        <!-- Header -->
                        <div :class="`bg-gradient-to-br ${planColors[idx % 3].from} ${planColors[idx % 3].to}`" class="p-8 text-white">
                            <span class="text-4xl block mb-3">{{ planIcons[idx % 3] }}</span>
                            <h2 class="text-2xl font-extrabold mb-1">{{ plan.nombre }}</h2>
                            <div class="flex items-baseline gap-2 mt-3">
                                <span class="text-4xl font-extrabold">{{ fmt(plan.monto_sugerido) }}</span>
                                <span class="text-white/75 text-sm">/{{ freqLabel(plan.frecuencia) }}</span>
                            </div>
                        </div>
                        <!-- Body -->
                        <div class="p-8 flex-1 flex flex-col">
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ plan.descripcion }}</p>
                            <div class="mb-6">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ t('plans.benefits') }}</p>
                                <ul class="space-y-2.5">
                                    <li v-for="b in (Array.isArray(plan.beneficios) ? plan.beneficios : (plan.beneficios ? plan.beneficios.split('\n') : []))"
                                        :key="b" class="flex items-start gap-2 text-sm">
                                        <span class="text-teal-500 mt-0.5 font-bold flex-shrink-0">✓</span>
                                        <span class="text-slate-600">{{ b }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-auto">
                                <Link :href="plan.id ? `/donar?plan=${plan.id}` : '/donar'"
                                    :class="plan.destacado ? 'bg-teal-600 text-white hover:bg-teal-700 shadow-lg shadow-teal-500/20' : 'border-2 border-teal-600 text-teal-600 hover:bg-teal-50'"
                                    class="block text-center w-full rounded-xl py-3 text-sm font-bold transition">
                                    {{ t('plans.join') }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Monto libre -->
        <section class="py-16 bg-white border-t border-slate-100">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl font-extrabold text-slate-800 mb-2">{{ t('plans.custom') }}</h2>
                <p class="text-slate-500 mb-8">¿Prefieres elegir tu propio monto? No hay problema, cada aportación cuenta.</p>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2 text-left">Monto (MXN)</label>
                            <input v-model="montoLibre" type="number" min="10" placeholder="$ 200"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2 text-left">Frecuencia</label>
                            <select v-model="frecuenciaLibre" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-teal-400 focus:outline-none">
                                <option value="unica">{{ t('plans.frequency.unica') }}</option>
                                <option value="mensual">{{ t('plans.frequency.mensual') }}</option>
                                <option value="anual">{{ t('plans.frequency.anual') }}</option>
                            </select>
                        </div>
                    </div>
                    <Link :href="`/donar?monto=${montoLibre}&frecuencia=${frecuenciaLibre}`"
                        class="block w-full text-center rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 py-3 text-sm font-bold text-white hover:from-teal-700 transition shadow-lg shadow-teal-500/20">
                        ❤️ Donar {{ montoLibre ? fmt(montoLibre) : '' }}
                    </Link>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-16 bg-slate-50">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-extrabold text-slate-800 text-center mb-10">Preguntas frecuentes</h2>
                <div class="space-y-4">
                    <div v-for="q in [
                        { q: '¿Puedo cancelar mi donación recurrente?', a: 'Sí, en cualquier momento desde tu portal de donador o contactando a nuestro equipo.' },
                        { q: '¿Cómo sé que mi dinero llega a donde debe?', a: 'Publicamos reportes de transparencia detallados cada trimestre con el destino exacto de cada peso.' },
                        { q: '¿Las donaciones son deducibles de impuestos?', a: 'Sí, emitimos comprobantes fiscales (CFDI) para todas las donaciones. Puedes descargarlos en tu portal.' },
                        { q: '¿Es seguro pagar en línea?', a: 'Absolutamente. Usamos Stripe, el estándar mundial en seguridad de pagos. Nunca almacenamos datos de tarjeta.' },
                    ]" :key="q.q"
                        class="bg-white rounded-2xl border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-800 mb-2">{{ q.q }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ q.a }}</p>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
