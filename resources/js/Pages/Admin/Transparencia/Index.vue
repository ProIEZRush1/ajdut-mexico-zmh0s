<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ reportes: Array, flash: Object });

const showForm = ref(false);
const editando = ref(null);

const form = useForm({
    titulo: '',
    tipo: 'anual',
    anio: new Date().getFullYear(),
    trimestre: '',
    descripcion: '',
    total_recaudado: '',
    total_gastado: '',
    donadores_activos: 0,
    beneficiarios: 0,
    publicado: false,
});

const abrirEditar = (r) => {
    editando.value = r;
    form.titulo = r.titulo;
    form.tipo = r.tipo;
    form.anio = r.anio;
    form.trimestre = r.trimestre;
    form.descripcion = r.descripcion;
    form.total_recaudado = r.total_recaudado;
    form.total_gastado = r.total_gastado;
    form.donadores_activos = r.donadores_activos;
    form.beneficiarios = r.beneficiarios;
    form.publicado = r.publicado;
    showForm.value = true;
};

const cancelar = () => { editando.value = null; showForm.value = false; form.reset(); };

const submit = () => {
    if (editando.value) {
        form.put(`/admin/transparencia/${editando.value.id}`, { onSuccess: cancelar });
    } else {
        form.post('/admin/transparencia', { onSuccess: cancelar });
    }
};

const deleteForm = useForm({});
const eliminar = (id) => {
    if (confirm('¿Eliminar este reporte?')) deleteForm.delete(`/admin/transparencia/${id}`);
};

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);
</script>

<template>
    <Head title="Transparencia — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-bold text-slate-800">Reportes de Transparencia</h2></template>

        <div class="mx-auto max-w-7xl space-y-6">
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">{{ flash.success }}</div>

            <div class="flex justify-end">
                <button @click="showForm = !showForm; editando = null; form.reset()"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-2 text-sm font-bold text-white shadow hover:from-teal-700">
                    + Nuevo Reporte
                </button>
            </div>

            <!-- Form -->
            <div v-if="showForm" class="rounded-2xl border border-teal-200 bg-teal-50 p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">{{ editando ? 'Editar Reporte' : 'Nuevo Reporte de Transparencia' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Título *</label>
                        <input v-model="form.titulo" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tipo</label>
                            <select v-model="form.tipo" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                                <option value="anual">Anual</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="causa">Por Causa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Año *</label>
                            <input v-model="form.anio" type="number" min="2000" max="2100" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" required />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Trimestre</label>
                            <input v-model="form.trimestre" type="text" placeholder="Q1, Q2..." class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Total Recaudado (MXN)</label>
                            <input v-model="form.total_recaudado" type="number" min="0" step="0.01" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Total Gastado (MXN)</label>
                            <input v-model="form.total_gastado" type="number" min="0" step="0.01" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Donadores Activos</label>
                            <input v-model="form.donadores_activos" type="number" min="0" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Beneficiarios</label>
                            <input v-model="form.beneficiarios" type="number" min="0" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción</label>
                        <textarea v-model="form.descripcion" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none"></textarea>
                    </div>
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                        <input v-model="form.publicado" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600" />
                        Publicar en portal
                    </label>
                    <div class="flex gap-3">
                        <button type="button" @click="cancelar" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</button>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2 text-sm font-bold text-white shadow hover:from-teal-700 disabled:opacity-50">
                            {{ editando ? 'Actualizar' : 'Crear Reporte' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Título</th>
                            <th class="px-5 py-3 text-left hidden md:table-cell">Año</th>
                            <th class="px-5 py-3 text-left hidden lg:table-cell">Recaudado</th>
                            <th class="px-5 py-3 text-left hidden lg:table-cell">Beneficiarios</th>
                            <th class="px-5 py-3 text-center">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="r in reportes" :key="r.id" class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ r.titulo }}</p>
                                <p class="text-xs text-slate-400 capitalize">{{ r.tipo }}</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell text-slate-600">{{ r.anio }}{{ r.trimestre ? ' · ' + r.trimestre : '' }}</td>
                            <td class="px-5 py-4 hidden lg:table-cell font-semibold text-teal-600">{{ fmt(r.total_recaudado) }}</td>
                            <td class="px-5 py-4 hidden lg:table-cell text-slate-600">{{ r.beneficiarios?.toLocaleString() }}</td>
                            <td class="px-5 py-4 text-center">
                                <span :class="r.publicado ? 'bg-teal-100 text-teal-700' : 'bg-slate-100 text-slate-500'"
                                    class="rounded-full px-2.5 py-0.5 text-xs font-semibold">
                                    {{ r.publicado ? 'Publicado' : 'Borrador' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="abrirEditar(r)" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-teal-100 hover:text-teal-700">Editar</button>
                                    <button @click="eliminar(r.id)" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!reportes?.length">
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">No hay reportes de transparencia aún.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
