<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    causas: Array,
    planes: Array,
    causa_preseleccionada: Object,
    trial_locked: Boolean,
});

const page = usePage();

const form = useForm({
    nombre: '',
    apellido: '',
    email: '',
    monto: '',
    frecuencia: 'unica',
    causa_id: props.causa_preseleccionada?.id ?? '',
    plan_id: '',
});

const seleccionarPlan = (plan) => {
    form.plan_id = plan.id;
    form.monto = plan.monto_sugerido ?? form.monto;
    form.frecuencia = plan.frecuencia;
};

const showTrialMsg = ref(false);

const submit = () => {
    if (props.trial_locked) {
        showTrialMsg.value = true;
        return;
    }
    form.post('/donar/checkout');
};

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);
const pct = (rec, meta) => meta > 0 ? Math.min(100, Math.round((rec / meta) * 100)) : 0;
</script>

<template>
    <Head title="Hacer una Donación — AJDUT Mexico" />
    <GuestLayout>
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900">Haz una Donación</h1>
            <p class="mt-1 text-sm text-slate-500">Tu generosidad transforma vidas.</p>
        </div>

        <!-- Trial locked banner -->
        <div v-if="showTrialMsg" class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xl">🔒</span>
                <p class="text-sm font-bold text-amber-800">Función disponible al confirmar tu proyecto</p>
            </div>
            <p class="text-xs text-amber-700">Los pagos reales se activan al confirmar tu anticipo con el equipo de Overcloud. Mientras tanto puedes explorar y configurar todo.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Causa -->
            <div v-if="causas?.length">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Elige una causa (opcional)</label>
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
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nombre *</label>
                    <input v-model="form.nombre" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Apellido *</label>
                    <input v-model="form.apellido" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Correo electrónico *</label>
                <input v-model="form.email" type="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
            </div>

            <!-- Monto y frecuencia -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Monto (MXN) *</label>
                    <input v-model="form.monto" type="number" min="10" step="0.01" placeholder="$ 200" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Frecuencia</label>
                    <select v-model="form.frecuencia" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                        <option value="unica">Única vez</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
            </div>

            <button type="submit" :disabled="form.processing"
                class="w-full rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-teal-500/20 hover:from-teal-700 disabled:opacity-50 flex items-center justify-center gap-2">
                <span v-if="trial_locked">🔒</span>
                {{ form.processing ? 'Procesando...' : 'Donar ahora' }}
            </button>

            <p class="text-xs text-center text-slate-400">Procesado de forma segura. Tu información está protegida.</p>
        </form>
    </GuestLayout>
</template>
