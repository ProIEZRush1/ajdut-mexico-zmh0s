<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ testimonios: Array, flash: Object });

const showForm = ref(false);
const editando = ref(null);

const form = useForm({
    nombre: '',
    cargo: '',
    organizacion: '',
    testimonio: '',
    estrellas: 5,
    activo: true,
    orden: 0,
});

const abrirEditar = (t) => {
    editando.value = t;
    form.nombre = t.nombre;
    form.cargo = t.cargo;
    form.organizacion = t.organizacion;
    form.testimonio = t.testimonio;
    form.estrellas = t.estrellas;
    form.activo = t.activo;
    form.orden = t.orden;
    showForm.value = true;
};

const cancelar = () => { editando.value = null; showForm.value = false; form.reset(); form.estrellas = 5; form.activo = true; };

const submit = () => {
    if (editando.value) {
        form.put(`/admin/testimonios/${editando.value.id}`, { onSuccess: cancelar });
    } else {
        form.post('/admin/testimonios', { onSuccess: cancelar });
    }
};

const deleteForm = useForm({});
const eliminar = (id) => {
    if (confirm('¿Eliminar este testimonio?')) deleteForm.delete(`/admin/testimonios/${id}`);
};
</script>

<template>
    <Head title="Testimonios — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-bold text-slate-800">Testimonios</h2></template>

        <div class="mx-auto max-w-5xl space-y-6">
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">{{ flash.success }}</div>

            <div class="flex justify-end">
                <button @click="showForm = !showForm; editando = null; form.reset(); form.estrellas = 5; form.activo = true;"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-2 text-sm font-bold text-white shadow hover:from-teal-700">
                    + Agregar Testimonio
                </button>
            </div>

            <div v-if="showForm" class="rounded-2xl border border-teal-200 bg-teal-50 p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">{{ editando ? 'Editar Testimonio' : 'Nuevo Testimonio' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre *</label>
                            <input v-model="form.nombre" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Cargo</label>
                            <input v-model="form.cargo" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Organización</label>
                        <input v-model="form.organizacion" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Testimonio *</label>
                        <textarea v-model="form.testimonio" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required></textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estrellas</label>
                            <select v-model="form.estrellas" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                                <option value="5">⭐⭐⭐⭐⭐ 5</option>
                                <option value="4">⭐⭐⭐⭐ 4</option>
                                <option value="3">⭐⭐⭐ 3</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Orden</label>
                            <input v-model="form.orden" type="number" min="0" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                        <div class="flex items-end pb-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                                <input v-model="form.activo" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600" />
                                Activo
                            </label>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="cancelar" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</button>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2 text-sm font-bold text-white shadow hover:from-teal-700 disabled:opacity-50">
                            {{ editando ? 'Actualizar' : 'Agregar' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="t in testimonios" :key="t.id"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-bold text-slate-800">{{ t.nombre }}</p>
                            <p class="text-xs text-slate-500">{{ t.cargo }}{{ t.organizacion ? ' · ' + t.organizacion : '' }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <span v-for="i in t.estrellas" :key="i" class="text-amber-400 text-xs">⭐</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 italic mb-4">"{{ t.testimonio }}"</p>
                    <div class="flex items-center justify-between">
                        <span :class="t.activo ? 'bg-teal-100 text-teal-700' : 'bg-slate-100 text-slate-500'"
                            class="rounded-full px-2.5 py-0.5 text-xs font-semibold">{{ t.activo ? 'Activo' : 'Inactivo' }}</span>
                        <div class="flex gap-2">
                            <button @click="abrirEditar(t)" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-teal-100 hover:text-teal-700">Editar</button>
                            <button @click="eliminar(t.id)" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">×</button>
                        </div>
                    </div>
                </div>

                <div v-if="!testimonios?.length" class="col-span-2 rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                    <p class="text-slate-400">No hay testimonios aún. Agrega el primero.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
