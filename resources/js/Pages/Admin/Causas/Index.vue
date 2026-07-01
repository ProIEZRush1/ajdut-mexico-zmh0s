<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    causas: Object,
    filters: Object,
    flash: Object,
});

const search = ref(props.filters?.q ?? '');
const filterActiva = ref(props.filters?.activa ?? '');

const doSearch = () => {
    router.get('/admin/causas', { q: search.value, activa: filterActiva.value }, { preserveState: true, replace: true });
};

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);
const pct = (rec, meta) => meta > 0 ? Math.min(100, Math.round((rec / meta) * 100)) : 0;

const deleteForm = useForm({});
const eliminar = (id) => {
    if (confirm('¿Confirmas eliminar esta causa?')) {
        deleteForm.delete(`/admin/causas/${id}`);
    }
};
</script>

<template>
    <Head title="Causas — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold text-slate-800">Causas</h2>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Alerts -->
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">
                {{ flash.success }}
            </div>
            <div v-if="flash?.error" class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-medium text-red-800">
                {{ flash.error }}
            </div>

            <!-- Header actions -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex gap-2 flex-wrap">
                    <input v-model="search" @keyup.enter="doSearch" type="text" placeholder="Buscar causa..."
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-teal-400 focus:outline-none focus:ring-1 focus:ring-teal-400" />
                    <select v-model="filterActiva" @change="doSearch"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-400 focus:outline-none">
                        <option value="">Todas</option>
                        <option value="1">Activas</option>
                        <option value="0">Inactivas</option>
                    </select>
                    <button @click="doSearch" class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Filtrar</button>
                </div>
                <Link href="/admin/causas/crear"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-2 text-sm font-bold text-white shadow hover:from-teal-700 hover:to-emerald-700">
                    + Nueva Causa
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Causa</th>
                            <th class="px-5 py-3 text-left hidden md:table-cell">Categoría</th>
                            <th class="px-5 py-3 text-left hidden lg:table-cell">Progreso</th>
                            <th class="px-5 py-3 text-left hidden sm:table-cell">Meta</th>
                            <th class="px-5 py-3 text-center">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="causa in causas.data" :key="causa.id" class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ causa.titulo }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 hidden sm:block">{{ causa.descripcion_corta?.substring(0, 60) }}...</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="rounded-full bg-teal-100 px-2.5 py-0.5 text-xs font-medium text-teal-700">{{ causa.categoria }}</span>
                            </td>
                            <td class="px-5 py-4 hidden lg:table-cell min-w-[140px]">
                                <div class="text-xs font-semibold text-slate-600 mb-1">{{ pct(causa.recaudado, causa.meta_recaudacion) }}%</div>
                                <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-1.5 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500" :style="{ width: pct(causa.recaudado, causa.meta_recaudacion) + '%' }"></div>
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ fmt(causa.recaudado) }}</div>
                            </td>
                            <td class="px-5 py-4 hidden sm:table-cell text-slate-600 font-medium">{{ fmt(causa.meta_recaudacion) }}</td>
                            <td class="px-5 py-4 text-center">
                                <span :class="causa.activa ? 'bg-teal-100 text-teal-700' : 'bg-slate-100 text-slate-500'"
                                    class="rounded-full px-2.5 py-0.5 text-xs font-semibold">
                                    {{ causa.activa ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="`/admin/causas/${causa.id}/editar`"
                                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-teal-100 hover:text-teal-700 transition">
                                        Editar
                                    </Link>
                                    <button @click="eliminar(causa.id)"
                                        class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100 transition">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!causas.data?.length">
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">No hay causas. <Link href="/admin/causas/crear" class="text-teal-600 font-medium">Crea la primera</Link>.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="causas.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in causas.links" :key="link.label" :href="link.url ?? '#'"
                    :class="['rounded-lg px-3 py-1.5 text-sm font-medium transition', link.active ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50', !link.url ? 'opacity-40 cursor-not-allowed' : '']"
                    v-html="link.label" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
