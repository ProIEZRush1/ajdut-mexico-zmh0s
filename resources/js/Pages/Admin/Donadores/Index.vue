<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ donadores: Object, filters: Object, flash: Object });

const search = ref(props.filters?.q ?? '');
const filterEstado = ref(props.filters?.estado ?? '');

const doSearch = () => {
    router.get('/admin/donadores', { q: search.value, estado: filterEstado.value }, { preserveState: true, replace: true });
};

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);

const deleteForm = useForm({});
const eliminar = (id) => {
    if (confirm('¿Eliminar este donador?')) deleteForm.delete(`/admin/donadores/${id}`);
};

const estadoColor = { activo: 'bg-teal-100 text-teal-700', inactivo: 'bg-slate-100 text-slate-500', cancelado: 'bg-red-100 text-red-700' };
</script>

<template>
    <Head title="Donadores — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-bold text-slate-800">Donadores</h2></template>

        <div class="mx-auto max-w-7xl space-y-6">
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">{{ flash.success }}</div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex gap-2 flex-wrap">
                    <input v-model="search" @keyup.enter="doSearch" type="text" placeholder="Nombre, email..."
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-teal-400 focus:outline-none" />
                    <select v-model="filterEstado" @change="doSearch"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-400 focus:outline-none">
                        <option value="">Todos</option>
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                        <option value="cancelado">Cancelados</option>
                    </select>
                    <button @click="doSearch" class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Buscar</button>
                </div>
                <a href="/admin/donadores/export-csv"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">
                    ↓ Exportar CSV
                </a>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Donador</th>
                            <th class="px-5 py-3 text-left hidden md:table-cell">Plan</th>
                            <th class="px-5 py-3 text-left hidden lg:table-cell">Total Donado</th>
                            <th class="px-5 py-3 text-center">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="d in donadores.data" :key="d.id" class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ d.nombre }} {{ d.apellido }}</p>
                                <p class="text-xs text-slate-400">{{ d.email }}</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="text-sm text-slate-600">{{ d.plan_actual ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-4 hidden lg:table-cell font-semibold text-teal-600">{{ fmt(d.total_donado) }}</td>
                            <td class="px-5 py-4 text-center">
                                <span :class="estadoColor[d.estado] ?? 'bg-slate-100 text-slate-500'"
                                    class="rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize">{{ d.estado }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="`/admin/donadores/${d.id}`"
                                        class="rounded-lg bg-teal-50 px-3 py-1.5 text-xs font-medium text-teal-700 hover:bg-teal-100">Ver</Link>
                                    <button @click="eliminar(d.id)"
                                        class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!donadores.data?.length">
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">No hay donadores registrados aún.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="donadores.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in donadores.links" :key="link.label" :href="link.url ?? '#'"
                    :class="['rounded-lg px-3 py-1.5 text-sm font-medium transition', link.active ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50', !link.url ? 'opacity-40 cursor-not-allowed' : '']"
                    v-html="link.label" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
