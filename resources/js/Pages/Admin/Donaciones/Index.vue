<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ donaciones: Object, filters: Object, flash: Object, totales: Object });

const search = ref(props.filters?.q ?? '');
const filterEstado = ref(props.filters?.estado ?? '');

const doSearch = () => {
    router.get('/admin/donaciones', { q: search.value, estado: filterEstado.value }, { preserveState: true, replace: true });
};

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);
const fmtDate = (d) => d ? new Date(d).toLocaleDateString('es-MX') : '—';

const deleteForm = useForm({});
const reciboForm = useForm({});

const eliminar = (id) => {
    if (confirm('¿Eliminar esta donación?')) deleteForm.delete(`/admin/donaciones/${id}`);
};
const emitirRecibo = (id) => reciboForm.post(`/admin/donaciones/${id}/recibo`);

const estadoColor = { completada: 'bg-teal-100 text-teal-700', pendiente: 'bg-amber-100 text-amber-700', fallida: 'bg-red-100 text-red-700' };
const frecLabel = { unica: 'Única', mensual: 'Mensual', anual: 'Anual' };
</script>

<template>
    <Head title="Donaciones — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-bold text-slate-800">Donaciones</h2></template>

        <div class="mx-auto max-w-7xl space-y-6">
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">{{ flash.success }}</div>

            <!-- Totales -->
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                    <p class="text-xs text-slate-500 uppercase tracking-wide">Total Recaudado</p>
                    <p class="text-2xl font-extrabold text-teal-600 mt-1">{{ fmt(totales?.total) }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                    <p class="text-xs text-slate-500 uppercase tracking-wide">Donaciones Completadas</p>
                    <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ totales?.count ?? 0 }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex gap-2 flex-wrap">
                    <input v-model="search" @keyup.enter="doSearch" type="text" placeholder="Buscar folio..."
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-teal-400 focus:outline-none" />
                    <select v-model="filterEstado" @change="doSearch"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-400 focus:outline-none">
                        <option value="">Todas</option>
                        <option value="completada">Completadas</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="fallida">Fallidas</option>
                    </select>
                </div>
                <Link href="/admin/donaciones/crear"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-2 text-sm font-bold text-white shadow hover:from-teal-700">
                    + Registrar Donación
                </Link>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Folio</th>
                            <th class="px-5 py-3 text-left hidden md:table-cell">Donador</th>
                            <th class="px-5 py-3 text-left hidden lg:table-cell">Causa</th>
                            <th class="px-5 py-3 text-left">Monto</th>
                            <th class="px-5 py-3 text-center hidden sm:table-cell">Frecuencia</th>
                            <th class="px-5 py-3 text-center">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="d in donaciones.data" :key="d.id" class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <p class="font-mono text-xs font-semibold text-slate-700">{{ d.folio }}</p>
                                <p class="text-xs text-slate-400">{{ fmtDate(d.created_at) }}</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="text-sm text-slate-700">{{ d.donador ? `${d.donador.nombre} ${d.donador.apellido}` : 'Anónimo' }}</span>
                            </td>
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <span class="text-sm text-slate-600">{{ d.causa?.titulo ?? 'General' }}</span>
                            </td>
                            <td class="px-5 py-4 font-bold text-teal-600">{{ fmt(d.monto) }}</td>
                            <td class="px-5 py-4 text-center hidden sm:table-cell">
                                <span class="text-xs text-slate-500">{{ frecLabel[d.frecuencia] ?? d.frecuencia }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span :class="estadoColor[d.estado] ?? 'bg-slate-100 text-slate-500'"
                                    class="rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize">{{ d.estado }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button v-if="d.estado === 'completada' && !d.recibo_emitido" @click="emitirRecibo(d.id)"
                                        class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100">Recibo</button>
                                    <button @click="eliminar(d.id)"
                                        class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">×</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!donaciones.data?.length">
                            <td colspan="7" class="px-5 py-12 text-center text-sm text-slate-400">No hay donaciones registradas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="donaciones.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in donaciones.links" :key="link.label" :href="link.url ?? '#'"
                    :class="['rounded-lg px-3 py-1.5 text-sm font-medium transition', link.active ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50', !link.url ? 'opacity-40 cursor-not-allowed' : '']"
                    v-html="link.label" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
