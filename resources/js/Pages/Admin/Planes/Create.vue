<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    nombre: '',
    descripcion: '',
    monto_sugerido: '',
    monto_libre: false,
    frecuencia: 'mensual',
    beneficios: [],
    color: '#0d9488',
    icono: '❤️',
    activo: true,
    orden: 0,
});

const nuevoBeneficio = ref('');
const agregarBeneficio = () => {
    if (nuevoBeneficio.value.trim()) {
        form.beneficios.push(nuevoBeneficio.value.trim());
        nuevoBeneficio.value = '';
    }
};
const quitarBeneficio = (i) => form.beneficios.splice(i, 1);

const submit = () => form.post('/admin/planes');
</script>

<template>
    <Head title="Nuevo Plan — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/planes" class="text-slate-400 hover:text-slate-600">Planes</Link>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-800">Nuevo Plan</span>
            </div>
        </template>

        <div class="mx-auto max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre *</label>
                        <input v-model="form.nombre" type="text" placeholder="Amigo, Padrino, Benefactor..." class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                        <InputError :message="form.errors.nombre" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Ícono</label>
                        <input v-model="form.icono" type="text" placeholder="❤️" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción *</label>
                    <textarea v-model="form.descripcion" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Monto sugerido (MXN)</label>
                        <input v-model="form.monto_sugerido" type="number" min="0" step="0.01" :disabled="form.monto_libre"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none disabled:bg-slate-50" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Frecuencia *</label>
                        <select v-model="form.frecuencia" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option value="mensual">Mensual</option>
                            <option value="anual">Anual</option>
                            <option value="unica">Única vez</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Color de marca</label>
                        <input v-model="form.color" type="color" class="h-10 w-full rounded-xl border border-slate-200 px-2 cursor-pointer" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Orden</label>
                        <input v-model="form.orden" type="number" min="0" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Beneficios</label>
                    <div class="flex gap-2 mb-2">
                        <input v-model="nuevoBeneficio" type="text" placeholder="Agregar beneficio..." @keyup.enter.prevent="agregarBeneficio"
                            class="flex-1 rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-teal-400 focus:outline-none" />
                        <button type="button" @click="agregarBeneficio" class="rounded-xl bg-teal-50 px-3 py-2 text-sm font-medium text-teal-700 hover:bg-teal-100">+</button>
                    </div>
                    <ul class="space-y-1">
                        <li v-for="(b, i) in form.beneficios" :key="i" class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-sm">
                            <span>{{ b }}</span>
                            <button type="button" @click="quitarBeneficio(i)" class="text-red-400 hover:text-red-600 font-bold">×</button>
                        </li>
                    </ul>
                </div>

                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                        <input v-model="form.activo" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600" />
                        Plan activo
                    </label>
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                        <input v-model="form.monto_libre" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-amber-500" />
                        Permitir monto libre
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <Link href="/admin/planes" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</Link>
                    <button type="submit" :disabled="form.processing"
                        class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:from-teal-700 disabled:opacity-50">
                        Crear Plan
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
