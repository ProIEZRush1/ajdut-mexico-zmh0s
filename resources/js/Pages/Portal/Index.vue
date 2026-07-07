<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({
    donador: Object,
    donaciones: Array,
    recibos: Array,
    suscripcion_activa: Object,
})

const { t } = useI18n()
const page = usePage()
const activeTab = ref('history')

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const fmtDate = (d) => d ? new Date(d).toLocaleDateString('es-MX', { year: 'numeric', month: 'long', day: 'numeric' }) : '-'

const estadoBadge = (e) => ({
    completada: 'bg-teal-100 text-teal-700',
    pendiente: 'bg-amber-100 text-amber-700',
    fallida: 'bg-red-100 text-red-700',
    reembolsada: 'bg-slate-100 text-slate-600',
}[e] ?? 'bg-slate-100 text-slate-600')

const profileForm = useForm({
    nombre: props.donador?.nombre ?? '',
    apellido: props.donador?.apellido ?? '',
    telefono: props.donador?.telefono ?? '',
    rfc: props.donador?.rfc ?? '',
    razon_social: props.donador?.razon_social ?? '',
})

const submitProfile = () => profileForm.patch('/portal/perfil')

const tabs = computed(() => [
    { id: 'history', label: t('portal.history'), icon: '📋' },
    { id: 'receipts', label: t('portal.receipts'), icon: '📄' },
    { id: 'plan', label: t('portal.plan'), icon: '🔄' },
    { id: 'profile', label: t('portal.profile'), icon: '👤' },
])
</script>

<template>
    <Head :title="t('portal.title')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold tracking-tight text-slate-800">{{ t('portal.title') }}</h2>
        </template>

        <div class="mx-auto max-w-5xl space-y-6">
            <!-- No donador -->
            <div v-if="!donador" class="rounded-2xl border border-amber-200 bg-amber-50 p-8 text-center">
                <p class="text-4xl mb-4">🔍</p>
                <h3 class="text-lg font-bold text-amber-800 mb-2">{{ t('portal.no_donor') }}</h3>
                <p class="text-sm text-amber-700">Para tener acceso al portal, realiza tu primera donación y tu cuenta quedará vinculada automáticamente.</p>
            </div>

            <template v-else>
                <!-- Welcome card -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-700 via-teal-600 to-coral-600 p-6 text-white shadow-xl shadow-teal-500/20">
                    <span class="pointer-events-none absolute -right-4 -top-4 text-7xl opacity-20">❤️</span>
                    <div class="relative flex items-center gap-4">
                        <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl font-bold text-white">
                            {{ (donador.nombre ?? '?')[0] }}
                        </div>
                        <div>
                            <p class="text-sm text-white/70 font-medium">Bienvenido/a de nuevo</p>
                            <h2 class="font-serif text-2xl font-extrabold">{{ donador.nombre }} {{ donador.apellido }}</h2>
                            <p class="text-white/80 text-sm mt-0.5">Total donado: <strong>{{ fmt(donador.total_donado) }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="flex overflow-x-auto border-b border-slate-200">
                        <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id"
                            :class="activeTab === tab.id
                                ? 'border-b-2 border-coral-600 text-coral-700 bg-coral-50 font-bold'
                                : 'text-slate-500 hover:text-slate-700 font-medium'"
                            class="flex items-center gap-2 px-5 py-4 text-sm whitespace-nowrap transition flex-shrink-0">
                            <span>{{ tab.icon }}</span>
                            {{ tab.label }}
                        </button>
                    </div>

                    <div class="p-6">
                        <!-- Historial -->
                        <div v-if="activeTab === 'history'">
                            <div v-if="donaciones?.length" class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                            <th class="pb-3 pr-4">Folio</th>
                                            <th class="pb-3 pr-4">Causa</th>
                                            <th class="pb-3 pr-4">Monto</th>
                                            <th class="pb-3 pr-4">Frecuencia</th>
                                            <th class="pb-3 pr-4">Fecha</th>
                                            <th class="pb-3">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <tr v-for="d in donaciones" :key="d.id" class="hover:bg-slate-50 transition">
                                            <td class="py-3 pr-4 font-mono text-xs text-slate-600">{{ d.folio }}</td>
                                            <td class="py-3 pr-4 text-slate-700">{{ d.causa?.titulo ?? 'Fondo general' }}</td>
                                            <td class="py-3 pr-4 font-bold text-teal-700">{{ fmt(d.monto) }}</td>
                                            <td class="py-3 pr-4 capitalize text-slate-500">{{ d.frecuencia }}</td>
                                            <td class="py-3 pr-4 text-slate-400 whitespace-nowrap">{{ fmtDate(d.fecha_pago ?? d.created_at) }}</td>
                                            <td class="py-3">
                                                <span :class="estadoBadge(d.estado)" class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize">{{ d.estado }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="text-center py-12 text-slate-400">
                                <p class="text-4xl mb-3">📋</p>
                                <p class="font-semibold">{{ t('portal.no_history') }}</p>
                            </div>
                        </div>

                        <!-- Recibos -->
                        <div v-if="activeTab === 'receipts'">
                            <div v-if="recibos?.length" class="space-y-3">
                                <div v-for="r in recibos" :key="r.id"
                                    class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:border-teal-200 transition">
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm">{{ r.folio_cfdi ?? r.folio }}</p>
                                        <p class="text-xs text-slate-400">{{ fmt(r.monto) }} · {{ fmtDate(r.fecha_emision ?? r.created_at) }}</p>
                                    </div>
                                    <a v-if="r.archivo_pdf" :href="`/portal/recibos/${r.id}/pdf`" target="_blank"
                                        class="btn-pop inline-flex items-center gap-1.5 rounded-xl bg-coral-50 border border-coral-200 px-4 py-2 text-sm font-semibold text-coral-700 hover:bg-coral-100 transition">
                                        ⬇️ {{ t('portal.download') }}
                                    </a>
                                    <span v-else class="text-xs text-slate-400 italic">Pendiente</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-12 text-slate-400">
                                <p class="text-4xl mb-3">📄</p>
                                <p class="font-semibold">{{ t('portal.no_receipts') }}</p>
                            </div>
                        </div>

                        <!-- Plan recurrente -->
                        <div v-if="activeTab === 'plan'">
                            <div v-if="suscripcion_activa" class="space-y-5">
                                <div class="rounded-2xl bg-teal-50 border border-teal-200 p-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="inline-block rounded-full bg-teal-600 text-white text-xs font-bold px-3 py-1 mb-3">Activo</span>
                                            <h3 class="text-lg font-bold text-slate-800">{{ suscripcion_activa.plan?.nombre ?? 'Plan personalizado' }}</h3>
                                            <p class="text-2xl font-extrabold text-teal-600 mt-1">{{ fmt(suscripcion_activa.monto) }}<span class="text-sm text-slate-400 font-normal">/{{ suscripcion_activa.frecuencia }}</span></p>
                                            <p v-if="suscripcion_activa.causa" class="text-sm text-slate-500 mt-1">Causa: {{ suscripcion_activa.causa.titulo }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-5 border-t border-teal-200">
                                        <p class="text-xs text-slate-500 mb-3">Para cancelar tu plan recurrente, haz clic en el botón de abajo. Esta acción detendrá los cargos futuros.</p>
                                        <button
                                            class="rounded-xl border border-red-300 bg-red-50 px-5 py-2 text-sm font-bold text-red-600 hover:bg-red-100 transition">
                                            ✕ {{ t('portal.cancel_plan') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12 text-slate-400">
                                <p class="text-4xl mb-3">🔄</p>
                                <p class="font-semibold mb-4">No tienes un plan recurrente activo.</p>
                                <a href="/donar" class="btn-pop inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-coral-500 to-coral-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-coral-500/20 hover:from-coral-600 transition">
                                    ❤️ Activar plan recurrente
                                </a>
                            </div>
                        </div>

                        <!-- Perfil -->
                        <div v-if="activeTab === 'profile'">
                            <form @submit.prevent="submitProfile" class="space-y-5 max-w-lg">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Nombre *</label>
                                        <input v-model="profileForm.nombre" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Apellido *</label>
                                        <input v-model="profileForm.apellido" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Teléfono</label>
                                    <input v-model="profileForm.telefono" type="tel" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">RFC</label>
                                    <input v-model="profileForm.rfc" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none uppercase" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Razón Social</label>
                                    <input v-model="profileForm.razon_social" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                                </div>
                                <button type="submit" :disabled="profileForm.processing"
                                    class="rounded-xl bg-teal-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-teal-700 transition disabled:opacity-50">
                                    {{ profileForm.processing ? 'Guardando...' : t('portal.save') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </AuthenticatedLayout>
</template>
