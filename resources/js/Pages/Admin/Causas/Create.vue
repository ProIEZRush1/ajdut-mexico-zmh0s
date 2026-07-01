<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    titulo: '',
    descripcion_corta: '',
    descripcion: '',
    meta_recaudacion: '',
    categoria: 'Educación',
    activa: true,
    destacada: false,
    fecha_inicio: '',
    fecha_fin: '',
    beneficiarios: 0,
    ubicacion: '',
});

const submit = () => form.post('/admin/causas');
</script>

<template>
    <Head title="Nueva Causa — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/causas" class="text-slate-400 hover:text-slate-600">Causas</Link>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-800">Nueva Causa</span>
            </div>
        </template>

        <div class="mx-auto max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Título *</label>
                    <input v-model="form.titulo" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none focus:ring-1 focus:ring-teal-400" required />
                    <InputError :message="form.errors.titulo" class="mt-1" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción corta *</label>
                    <textarea v-model="form.descripcion_corta" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none focus:ring-1 focus:ring-teal-400" required></textarea>
                    <InputError :message="form.errors.descripcion_corta" class="mt-1" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción completa *</label>
                    <textarea v-model="form.descripcion" rows="5" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none focus:ring-1 focus:ring-teal-400" required></textarea>
                    <InputError :message="form.errors.descripcion" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Meta de recaudación (MXN) *</label>
                        <input v-model="form.meta_recaudacion" type="number" min="0" step="0.01" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                        <InputError :message="form.errors.meta_recaudacion" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Categoría *</label>
                        <select v-model="form.categoria" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option>Educación</option>
                            <option>Salud</option>
                            <option>Desarrollo Comunitario</option>
                            <option>Alimentación</option>
                            <option>Vivienda</option>
                            <option>Medio Ambiente</option>
                            <option>Emergencias</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Beneficiarios estimados</label>
                        <input v-model="form.beneficiarios" type="number" min="0" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Ubicación</label>
                        <input v-model="form.ubicacion" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" placeholder="Estado o ciudad" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha inicio</label>
                        <input v-model="form.fecha_inicio" type="date" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha fin</label>
                        <input v-model="form.fecha_fin" type="date" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                </div>

                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                        <input v-model="form.activa" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500" />
                        Causa activa
                    </label>
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                        <input v-model="form.destacada" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-400" />
                        Destacada en inicio
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <Link href="/admin/causas" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                        Cancelar
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:from-teal-700 hover:to-emerald-700 disabled:opacity-50">
                        Crear Causa
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
