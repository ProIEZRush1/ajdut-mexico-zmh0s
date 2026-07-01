<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({
    causas: Array,
    planes: Array,
    causa_preseleccionada: Object,
    trial_locked: Boolean,
})

const { t } = useI18n()
const page = usePage()

const form = useForm({
    nombre: '',
    apellido: '',
    email: '',
    monto: '',
    frecuencia: 'unica',
    causa_id: props.causa_preseleccionada?.id ?? '',
    plan_id: '',
    firma_electronica: '',
    firma_fecha: '',
})

const seleccionarPlan = (plan) => {
    form.plan_id = plan.id
    form.monto = plan.monto_sugerido ?? form.monto
    form.frecuencia = plan.frecuencia
}

const showTrialMsg = ref(false)
const sigCanvas = ref(null)
const sigCtx = ref(null)
const isDrawing = ref(false)
const hasFirma = ref(false)
const firmaError = ref(false)

const esRecurrente = computed(() => form.frecuencia === 'mensual' || form.frecuencia === 'anual')

onMounted(() => {
    if (sigCanvas.value) {
        sigCtx.value = sigCanvas.value.getContext('2d')
        sigCtx.value.strokeStyle = '#1e293b'
        sigCtx.value.lineWidth = 2.5
        sigCtx.value.lineCap = 'round'
        sigCtx.value.lineJoin = 'round'
    }
})

watch(esRecurrente, (val) => {
    if (!val) {
        form.firma_electronica = ''
        hasFirma.value = false
        firmaError.value = false
    }
})

const getPos = (e, canvas) => {
    const rect = canvas.getBoundingClientRect()
    const src = e.touches ? e.touches[0] : e
    return { x: src.clientX - rect.left, y: src.clientY - rect.top }
}

const startDraw = (e) => {
    if (!sigCtx.value) return
    isDrawing.value = true
    const { x, y } = getPos(e, sigCanvas.value)
    sigCtx.value.beginPath()
    sigCtx.value.moveTo(x, y)
    e.preventDefault()
}

const draw = (e) => {
    if (!isDrawing.value || !sigCtx.value) return
    const { x, y } = getPos(e, sigCanvas.value)
    sigCtx.value.lineTo(x, y)
    sigCtx.value.stroke()
    hasFirma.value = true
    firmaError.value = false
    e.preventDefault()
}

const endDraw = () => {
    isDrawing.value = false
    if (hasFirma.value && sigCanvas.value) {
        form.firma_electronica = sigCanvas.value.toDataURL('image/png')
        form.firma_fecha = new Date().toISOString()
    }
}

const clearFirma = () => {
    if (sigCtx.value && sigCanvas.value) {
        sigCtx.value.clearRect(0, 0, sigCanvas.value.width, sigCanvas.value.height)
    }
    hasFirma.value = false
    form.firma_electronica = ''
    form.firma_fecha = ''
    firmaError.value = false
}

const submit = () => {
    if (props.trial_locked) { showTrialMsg.value = true; return }
    if (esRecurrente.value && !hasFirma.value) { firmaError.value = true; return }
    form.post('/donar/checkout')
}

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const pct = (rec, meta) => meta > 0 ? Math.min(100, Math.round((rec / meta) * 100)) : 0
</script>

<template>
    <Head :title="t('donate.title') + ' — AJDUT México'" />
    <GuestLayout>
        <div class="mb-6 text-center">
            <img src="/brand-logo.jpeg" alt="AJDUT México" class="h-14 w-14 rounded-xl object-contain mx-auto mb-3 shadow-sm" />
            <h1 class="text-2xl font-bold text-slate-900">{{ t('donate.title') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ t('donate.subtitle') }}</p>
        </div>

        <!-- Trial locked banner -->
        <div v-if="showTrialMsg" class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xl">🔒</span>
                <p class="text-sm font-bold text-amber-800">Función disponible al confirmar tu proyecto</p>
            </div>
            <p class="text-xs text-amber-700">Los pagos reales se activan al confirmar tu anticipo con el equipo de Overcloud.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Causa -->
            <div v-if="causas?.length">
                <label class="block text-xs font-semibold text-slate-600 mb-2">{{ t('donate.cause') }}</label>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <label v-for="c in causas" :key="c.id"
                        :class="form.causa_id == c.id ? 'border-teal-400 bg-teal-50' : 'border-slate-200 bg-white'"
                        class="flex items-center gap-3 rounded-xl border p-3 cursor-pointer transition hover:border-teal-300">
                        <input type="radio" :value="c.id" v-model="form.causa_id" class="h-4 w-4 text-teal-600" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ c.titulo }}</p>
                            <div class="h-1.5 rounded-full bg-slate-100 mt-1 overflow-hidden">
                                <div class="h-1.5 rounded-full bg-teal-500" :style="{ width: pct(c.recaudado, c.meta_recaudacion) + '%' }"></div>
                            </div>
                        </div>
                    </label>
                    <label :class="!form.causa_id ? 'border-teal-400 bg-teal-50' : 'border-slate-200 bg-white'"
                        class="flex items-center gap-3 rounded-xl border p-3 cursor-pointer transition hover:border-teal-300">
                        <input type="radio" value="" v-model="form.causa_id" class="h-4 w-4 text-teal-600" />
                        <p class="text-sm font-semibold text-slate-700">Donde más se necesite</p>
                    </label>
                </div>
            </div>

            <!-- Datos del donador -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.name') }} *</label>
                    <input v-model="form.nombre" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.lastname') }} *</label>
                    <input v-model="form.apellido" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.email') }} *</label>
                <input v-model="form.email" type="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
            </div>

            <!-- Monto y frecuencia -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.amount') }} *</label>
                    <input v-model="form.monto" type="number" min="10" step="0.01" placeholder="$ 200" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.frequency') }}</label>
                    <select v-model="form.frecuencia" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                        <option value="unica">Única vez</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
            </div>

            <!-- FIRMA ELECTRÓNICA (solo recurrentes) -->
            <transition name="slide">
                <div v-if="esRecurrente" class="rounded-2xl border border-teal-200 bg-teal-50 p-5 space-y-4">
                    <div>
                        <h3 class="text-sm font-bold text-teal-800 mb-1">✍️ {{ t('sig.title') }}</h3>
                        <p class="text-xs text-teal-700 leading-relaxed">{{ t('sig.body') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 mb-2">{{ t('sig.sign') }}:</p>
                        <div class="relative rounded-xl border-2 border-dashed border-slate-300 bg-white overflow-hidden"
                            :class="{ 'border-red-400': firmaError }">
                            <canvas
                                ref="sigCanvas"
                                width="360" height="120"
                                class="w-full touch-none cursor-crosshair block"
                                @mousedown="startDraw" @mousemove="draw" @mouseup="endDraw" @mouseleave="endDraw"
                                @touchstart="startDraw" @touchmove="draw" @touchend="endDraw"
                            ></canvas>
                            <div v-if="!hasFirma" class="pointer-events-none absolute inset-0 flex items-center justify-center">
                                <span class="text-slate-300 text-sm select-none">— Firma aquí —</span>
                            </div>
                        </div>
                        <p v-if="firmaError" class="text-xs text-red-500 mt-1">{{ t('sig.required') }}</p>
                        <button type="button" @click="clearFirma"
                            class="mt-2 text-xs text-slate-500 hover:text-slate-700 underline transition">
                            {{ t('sig.clear') }}
                        </button>
                    </div>
                </div>
            </transition>

            <button type="submit" :disabled="form.processing"
                class="w-full rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-teal-500/20 hover:from-teal-700 disabled:opacity-50 flex items-center justify-center gap-2">
                <span v-if="trial_locked">🔒</span>
                {{ form.processing ? 'Procesando...' : t('donate.btn') }}
            </button>

            <p class="text-xs text-center text-slate-400">🔒 Procesado de forma segura con Stripe. Tu información está protegida.</p>
        </form>
    </GuestLayout>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all .3s ease; overflow: hidden; }
.slide-enter-from, .slide-leave-to { opacity: 0; max-height: 0; }
.slide-enter-to, .slide-leave-from { opacity: 1; max-height: 400px; }
</style>
