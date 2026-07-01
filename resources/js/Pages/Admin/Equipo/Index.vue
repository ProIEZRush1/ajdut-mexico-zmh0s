<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ miembros: Array, flash: Object });

const showForm = ref(false);
const editando = ref(null);

const form = useForm({
    nombre: '',
    cargo: '',
    area: '',
    biografia: '',
    email: '',
    linkedin: '',
    activo: true,
    orden: 0,
});

const abrirEditar = (m) => {
    editando.value = m;
    Object.assign(form, { nombre: m.nombre, cargo: m.cargo, area: m.area, biografia: m.biografia, email: m.email, linkedin: m.linkedin, activo: m.activo, orden: m.orden });
    showForm.value = true;
};

const cancelar = () => { editando.value = null; showForm.value = false; form.reset(); form.activo = true; };

const submit = () => {
    if (editando.value) {
        form.put(`/admin/equipo/${editando.value.id}`, { onSuccess: cancelar });
    } else {
        form.post('/admin/equipo', { onSuccess: cancelar });
    }
};

const deleteForm = useForm({});
const eliminar = (id) => {
    if (confirm('¿Eliminar este miembro del equipo?')) deleteForm.delete(`/admin/equipo/${id}`);
};
</script>

<template>
    <Head title="Equipo — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-bold text-slate-800">Equipo AJDUT Mexico</h2></template>

        <div class="mx-auto max-w-5xl space-y-6">
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">{{ flash.success }}</div>

            <div class="flex justify-end">
                <button @click="showForm = !showForm; editando = null; form.reset(); form.activo = true;"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-2 text-sm font-bold text-white shadow hover:from-teal-700">
                    + Agregar Miembro
                </button>
            </div>

            <div v-if="showForm" class="rounded-2xl border border-teal-200 bg-teal-50 p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">{{ editando ? 'Editar Miembro' : 'Nuevo Miembro del Equipo' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre *</label>
                            <input v-model="form.nombre" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Cargo *</label>
                            <input v-model="form.cargo" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Área</label>
                            <input v-model="form.area" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                            <input v-model="form.email" type="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Biografía</label>
                        <textarea v-model="form.biografia" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">LinkedIn (URL)</label>
                            <input v-model="form.linkedin" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Orden</label>
                            <input v-model="form.orden" type="number" min="0" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                        <input v-model="form.activo" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600" />
                        Mostrar en el equipo
                    </label>
                    <div class="flex gap-3">
                        <button type="button" @click="cancelar" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</button>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2 text-sm font-bold text-white shadow hover:from-teal-700 disabled:opacity-50">
                            {{ editando ? 'Actualizar' : 'Agregar' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="m in miembros" :key="m.id"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-teal-500 to-emerald-600 text-xl font-bold text-white mb-4">
                        {{ m.nombre?.charAt(0) }}
                    </div>
                    <h3 class="font-bold text-slate-800">{{ m.nombre }}</h3>
                    <p class="text-sm text-teal-600 font-medium">{{ m.cargo }}</p>
                    <p v-if="m.area" class="text-xs text-slate-500 mt-0.5">{{ m.area }}</p>
                    <p v-if="m.biografia" class="text-xs text-slate-500 mt-3 leading-relaxed line-clamp-3">{{ m.biografia }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span :class="m.activo ? 'bg-teal-100 text-teal-700' : 'bg-slate-100 text-slate-500'"
                            class="rounded-full px-2.5 py-0.5 text-xs font-semibold">{{ m.activo ? 'Activo' : 'Inactivo' }}</span>
                        <div class="flex gap-2">
                            <button @click="abrirEditar(m)" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-teal-100 hover:text-teal-700">Editar</button>
                            <button @click="eliminar(m.id)" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">×</button>
                        </div>
                    </div>
                </div>

                <div v-if="!miembros?.length" class="col-span-3 rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                    <p class="text-slate-400">No hay miembros del equipo registrados.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
