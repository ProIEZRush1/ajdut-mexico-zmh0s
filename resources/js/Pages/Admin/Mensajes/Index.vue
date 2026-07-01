<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ mensajes: Object, filters: Object, flash: Object, sin_leer: Number });

const filterEstado = ref(props.filters?.estado ?? '');
const doFilter = () => router.get('/admin/mensajes', { estado: filterEstado.value }, { preserveState: true, replace: true });

const deleteForm = useForm({});
const eliminar = (id) => {
    if (confirm('¿Eliminar este mensaje?')) deleteForm.delete(`/admin/mensajes/${id}`);
};

const fmtDate = (d) => d ? new Date(d).toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';

const estadoColor = { nuevo: 'bg-amber-100 text-amber-700', leido: 'bg-slate-100 text-slate-600', respondido: 'bg-teal-100 text-teal-700' };
</script>

<template>
    <Head title="Mensajes de Contacto — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-slate-800">Mensajes de Contacto</h2>
                <span v-if="sin_leer > 0" class="rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-bold text-white">{{ sin_leer }} nuevos</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">{{ flash.success }}</div>

            <div class="flex gap-2">
                <select v-model="filterEstado" @change="doFilter"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-teal-400 focus:outline-none">
                    <option value="">Todos</option>
                    <option value="nuevo">Nuevos</option>
                    <option value="leido">Leídos</option>
                    <option value="respondido">Respondidos</option>
                </select>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Nombre</th>
                            <th class="px-5 py-3 text-left hidden md:table-cell">Asunto</th>
                            <th class="px-5 py-3 text-left hidden lg:table-cell">Fecha</th>
                            <th class="px-5 py-3 text-center">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="m in mensajes.data" :key="m.id" :class="m.estado === 'nuevo' ? 'bg-amber-50/50' : ''" class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ m.nombre }}</p>
                                <p class="text-xs text-slate-400">{{ m.email }}</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell text-slate-600">{{ m.asunto }}</td>
                            <td class="px-5 py-4 hidden lg:table-cell text-xs text-slate-400">{{ fmtDate(m.created_at) }}</td>
                            <td class="px-5 py-4 text-center">
                                <span :class="estadoColor[m.estado] ?? 'bg-slate-100 text-slate-500'"
                                    class="rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize">{{ m.estado }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="`/admin/mensajes/${m.id}`"
                                        class="rounded-lg bg-teal-50 px-3 py-1.5 text-xs font-medium text-teal-700 hover:bg-teal-100">Ver</Link>
                                    <button @click="eliminar(m.id)" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100">×</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!mensajes.data?.length">
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">No hay mensajes en esta bandeja.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="mensajes.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in mensajes.links" :key="link.label" :href="link.url ?? '#'"
                    :class="['rounded-lg px-3 py-1.5 text-sm font-medium transition', link.active ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50', !link.url ? 'opacity-40 cursor-not-allowed' : '']"
                    v-html="link.label" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
