<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ planes: Array, flash: Object });

const deleteForm = useForm({});
const eliminar = (id) => {
    if (confirm('¿Eliminar este plan?')) deleteForm.delete(`/admin/planes/${id}`);
};

const fmt = (n) => n ? new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n) : 'Libre';
const frecLabel = { mensual: 'Mensual', anual: 'Anual', unica: 'Única vez' };
</script>

<template>
    <Head title="Planes de Donación — AJDUT Mexico" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-bold text-slate-800">Planes de Donación</h2></template>

        <div class="mx-auto max-w-7xl space-y-6">
            <div v-if="flash?.success" class="rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-medium text-teal-800">{{ flash.success }}</div>

            <div class="flex justify-end">
                <Link href="/admin/planes/crear" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-2 text-sm font-bold text-white shadow hover:from-teal-700">
                    + Nuevo Plan
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="plan in planes" :key="plan.id"
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">{{ plan.icono }}</span>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">{{ plan.nombre }}</h3>
                                <span class="text-xs text-slate-500">{{ frecLabel[plan.frecuencia] ?? plan.frecuencia }}</span>
                            </div>
                        </div>
                        <span :class="plan.activo ? 'bg-teal-100 text-teal-700' : 'bg-slate-100 text-slate-500'"
                            class="rounded-full px-2.5 py-0.5 text-xs font-semibold">
                            {{ plan.activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <p class="text-3xl font-extrabold mb-1" :style="{ color: plan.color }">
                        {{ fmt(plan.monto_sugerido) }}
                        <span class="text-sm font-medium text-slate-400">/ {{ frecLabel[plan.frecuencia] }}</span>
                    </p>

                    <p class="text-sm text-slate-600 mb-4">{{ plan.descripcion }}</p>

                    <ul v-if="plan.beneficios?.length" class="space-y-1 mb-5">
                        <li v-for="b in plan.beneficios" :key="b" class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="text-teal-500">✓</span> {{ b }}
                        </li>
                    </ul>

                    <div class="flex gap-2">
                        <Link :href="`/admin/planes/${plan.id}/editar`"
                            class="flex-1 rounded-xl bg-slate-100 px-3 py-2 text-center text-xs font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-700">
                            Editar
                        </Link>
                        <button @click="eliminar(plan.id)"
                            class="flex-1 rounded-xl bg-red-50 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-100">
                            Eliminar
                        </button>
                    </div>
                </div>

                <div v-if="!planes?.length" class="col-span-3 rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                    <p class="text-slate-400">No hay planes de donación. <Link href="/admin/planes/crear" class="text-teal-600 font-medium">Crea el primero</Link>.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
