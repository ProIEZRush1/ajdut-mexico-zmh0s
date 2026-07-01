<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ donadores: Array, causas: Array, planes: Array });

const form = useForm({
    donador_id: '',
    causa_id: '',
    plan_id: '',
    monto: '',
    frecuencia: 'unica',
    estado: 'completada',
    metodo_pago: 'transferencia',
    notas: '',
});

const submit = () => form.post('/admin/donaciones');
</script>

<template>
    <Head title="Registrar Donación — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/donaciones" class="text-slate-400 hover:text-slate-600">Donaciones</Link>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-800">Registrar Donación</span>
            </div>
        </template>

        <div class="mx-auto max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Donador</label>
                    <select v-model="form.donador_id" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                        <option value="">-- Sin donador registrado --</option>
                        <option v-for="d in donadores" :key="d.id" :value="d.id">{{ d.nombre }} {{ d.apellido }} ({{ d.email }})</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Causa (opcional)</label>
                        <select v-model="form.causa_id" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option value="">-- General --</option>
                            <option v-for="c in causas" :key="c.id" :value="c.id">{{ c.titulo }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Plan (opcional)</label>
                        <select v-model="form.plan_id" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option value="">-- Sin plan --</option>
                            <option v-for="p in planes" :key="p.id" :value="p.id">{{ p.nombre }} (${{ p.monto_sugerido }})</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Monto (MXN) *</label>
                        <input v-model="form.monto" type="number" min="1" step="0.01"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                        <InputError :message="form.errors.monto" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Frecuencia *</label>
                        <select v-model="form.frecuencia" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option value="unica">Única vez</option>
                            <option value="mensual">Mensual</option>
                            <option value="anual">Anual</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Estado *</label>
                        <select v-model="form.estado" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option value="completada">Completada</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="fallida">Fallida</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Método de pago</label>
                        <select v-model="form.metodo_pago" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option value="stripe">Stripe</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Notas internas</label>
                    <textarea v-model="form.notas" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <Link href="/admin/donaciones" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</Link>
                    <button type="submit" :disabled="form.processing"
                        class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:from-teal-700 disabled:opacity-50">
                        Registrar Donación
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
