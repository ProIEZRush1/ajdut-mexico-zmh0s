<script setup>
import { ref, computed, onMounted } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { useI18n } from '@/composables/useI18n'

const props = defineProps({
    causas: Array,
    planes: Array,
    causa_preseleccionada: Object,
    monto_preseleccionado: Number,
    trial_locked: Boolean,
})

const { t, lang } = useI18n()

const montosSugeridos = [200, 500, 1000, 2000, 4000]

const form = useForm({
    nombre: '',
    apellido: '',
    email: '',
    monto: props.monto_preseleccionado ?? '',
    frecuencia: 'unica',
    causa_id: props.causa_preseleccionada?.id ?? '',
    plan_id: '',
    firma_nombre: '',
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
const nombreError = ref(false)

onMounted(() => {
    if (sigCanvas.value) {
        sigCtx.value = sigCanvas.value.getContext('2d')
        sigCtx.value.strokeStyle = '#1c3157'
        sigCtx.value.lineWidth = 2.5
        sigCtx.value.lineCap = 'round'
        sigCtx.value.lineJoin = 'round'
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

    nombreError.value = !form.firma_nombre.trim()
    firmaError.value = !hasFirma.value
    if (nombreError.value || firmaError.value) return

    form.post('/donar/checkout')
}

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const pct = (rec, meta) => meta > 0 ? Math.min(100, Math.round((rec / meta) * 100)) : 0
const frecuenciaLabel = computed(() => t(`plans.frequency.${form.frecuencia}`).toLowerCase())
const hoyTexto = computed(() => new Date().toLocaleDateString(lang.value === 'en' ? 'en-US' : 'es-MX', { year: 'numeric', month: 'long', day: 'numeric' }))
</script>

<template>
    <Head :title="t('donate.title') + ' — AJDUT México'" />
    <GuestLayout>
        <div class="mb-6 text-center">
            <img src="/brand-logo.svg" alt="AJDUT México" class="h-14 w-14 rounded-2xl object-contain mx-auto mb-3 shadow-md" />
            <span class="animate-heartbeat inline-block text-2xl mb-1">❤️</span>
            <h1 class="font-serif text-2xl font-bold text-slate-900 tracking-tight">{{ t('donate.title') }}</h1>
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
            <!-- Causa preseleccionada (ej. desde Jaguim) -->
            <div v-if="causa_preseleccionada" class="flex items-center gap-2 rounded-xl border border-coral-200 bg-coral-50 p-3">
                <span class="text-lg">❤️</span>
                <p class="text-sm text-slate-700">Estás donando para <span class="font-bold text-coral-700">{{ causa_preseleccionada.titulo }}</span></p>
            </div>

            <!-- Datos del donador -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.name') }} *</label>
                    <input v-model="form.nombre" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.lastname') }} *</label>
                    <input v-model="form.apellido" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.email') }} *</label>
                <input v-model="form.email" type="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
            </div>

            <!-- Montos sugeridos -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-2">Elige un monto</label>
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                    <button v-for="m in montosSugeridos" :key="m" type="button" @click="form.monto = m"
                        :class="Number(form.monto) === m ? 'border-coral-500 bg-coral-50 text-coral-700' : 'border-slate-200 text-slate-600 hover:border-coral-300'"
                        class="rounded-xl border py-2 text-sm font-bold transition">${{ m.toLocaleString('es-MX') }}</button>
                </div>
            </div>

            <!-- Monto y frecuencia -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.amount') }} *</label>
                    <input v-model="form.monto" type="number" min="10" step="0.01" placeholder="$ 200" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('donate.frequency') }}</label>
                    <select v-model="form.frecuencia" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none">
                        <option value="unica">Única vez</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
            </div>

            <!-- CARTA DE AUTORIZACIÓN DE CARGO A TARJETA -->
            <div class="rounded-2xl border-2 border-teal-100 bg-gradient-to-b from-teal-50/60 to-white p-5 sm:p-6 space-y-5">
                <div class="flex items-center gap-2 border-b border-teal-100 pb-3">
                    <span class="text-xl">📜</span>
                    <h3 class="font-serif text-base font-bold text-teal-900 tracking-tight">{{ t('sig.title') }}</h3>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ t('sig.legal_intro') }}
                    <strong class="text-teal-800">{{ form.firma_nombre.trim() || '_______________________' }}</strong>,
                    {{ t('sig.legal_authorize') }}
                    <strong class="text-teal-800">{{ fmt(form.monto) }}</strong>
                    {{ t('sig.legal_frequency') }}
                    <strong class="text-teal-800">{{ frecuenciaLabel }}</strong>.
                    {{ t('sig.legal_closing') }}
                </p>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">{{ t('sig.fullname') }} *</label>
                    <input v-model="form.firma_nombre" type="text" :placeholder="t('sig.fullname_placeholder')"
                        :class="nombreError ? 'border-red-400' : 'border-slate-200'"
                        class="w-full rounded-xl border px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
                    <p v-if="nombreError" class="text-xs text-red-500 mt-1">{{ t('sig.required_name') }}</p>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span class="font-semibold">{{ t('sig.date_label') }}</span>
                    <span>{{ hoyTexto }}</span>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-600 mb-2">{{ t('sig.sign') }}:</p>
                    <div class="relative rounded-xl border-2 border-dashed border-teal-200 bg-white overflow-hidden"
                        :class="{ 'border-red-400': firmaError }">
                        <canvas
                            ref="sigCanvas"
                            width="360" height="120"
                            class="w-full touch-none cursor-crosshair block"
                            @mousedown="startDraw" @mousemove="draw" @mouseup="endDraw" @mouseleave="endDraw"
                            @touchstart="startDraw" @touchmove="draw" @touchend="endDraw"
                        ></canvas>
                        <div v-if="!hasFirma" class="pointer-events-none absolute inset-0 flex items-center justify-center">
                            <span class="text-slate-300 text-sm select-none">— {{ t('sig.sign') }} —</span>
                        </div>
                    </div>
                    <p v-if="firmaError" class="text-xs text-red-500 mt-1">{{ t('sig.required') }}</p>
                    <button type="button" @click="clearFirma"
                        class="mt-2 text-xs text-slate-500 hover:text-slate-700 underline transition">
                        {{ t('sig.clear') }}
                    </button>
                </div>
            </div>

            <button type="submit" :disabled="form.processing"
                class="btn-pop w-full rounded-xl bg-gradient-to-r from-coral-500 to-coral-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-coral-500/30 hover:from-coral-600 disabled:opacity-50 flex items-center justify-center gap-2">
                <span v-if="trial_locked">🔒</span>
                <span v-else class="animate-heartbeat inline-block">❤️</span>
                {{ form.processing ? 'Procesando...' : t('donate.btn') }}
            </button>

            <p class="text-xs text-center text-slate-400">🔒 Procesado de forma segura con Stripe. Tu información está protegida.</p>
        </form>
    </GuestLayout>
</template>
