<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ categorias: Array });

const form = useForm({
    categoria_id: '',
    titulo: '',
    resumen: '',
    contenido: '',
    autor: '',
    publicada: false,
    fecha_publicacion: '',
});

const submit = () => form.post('/admin/noticias');
</script>

<template>
    <Head title="Nueva Noticia — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/noticias" class="text-slate-400 hover:text-slate-600">Noticias</Link>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-800">Nueva Noticia</span>
            </div>
        </template>

        <div class="mx-auto max-w-3xl">
            <form @submit.prevent="submit" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Título *</label>
                    <input v-model="form.titulo" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                    <InputError :message="form.errors.titulo" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Categoría</label>
                        <select v-model="form.categoria_id" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                            <option value="">-- Sin categoría --</option>
                            <option v-for="c in categorias" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Autor</label>
                        <input v-model="form.autor" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Resumen *</label>
                    <textarea v-model="form.resumen" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required></textarea>
                    <InputError :message="form.errors.resumen" class="mt-1" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Contenido *</label>
                    <textarea v-model="form.contenido" rows="10" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required></textarea>
                    <InputError :message="form.errors.contenido" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha de publicación</label>
                        <input v-model="form.fecha_publicacion" type="datetime-local" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                            <input v-model="form.publicada" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600" />
                            Publicar inmediatamente
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <Link href="/admin/noticias" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</Link>
                    <button type="submit" :disabled="form.processing"
                        class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:from-teal-700 disabled:opacity-50">
                        Guardar Noticia
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
