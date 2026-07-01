<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({ donador: Object });
const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);
const fmtDate = (d) => d ? new Date(d).toLocaleDateString('es-MX') : '—';
</script>

<template>
    <Head :title="`${donador.nombre} ${donador.apellido} — Donadores`" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/donadores" class="text-slate-400 hover:text-slate-600">Donadores</Link>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-800">{{ donador.nombre }} {{ donador.apellido }}</span>
            </div>
        </template>

        <div class="mx-auto max-w-4xl space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Info card -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-1">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-teal-500 to-emerald-600 text-2xl font-bold text-white">
                        {{ donador.nombre?.charAt(0) }}{{ donador.apellido?.charAt(0) }}
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">{{ donador.nombre }} {{ donador.apellido }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ donador.email }}</p>
                    <p v-if="donador.telefono" class="text-sm text-slate-500">{{ donador.telefono }}</p>

                    <div class="mt-4 space-y-2 border-t border-slate-100 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Estado</span>
                            <span class="font-semibold capitalize text-teal-600">{{ donador.estado }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Plan</span>
                            <span class="font-semibold text-slate-700">{{ donador.plan_actual ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Total donado</span>
                            <span class="font-bold text-teal-600">{{ fmt(donador.total_donado) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Primera donación</span>
                            <span class="text-slate-700">{{ fmtDate(donador.fecha_primer_donacion) }}</span>
                        </div>
                        <div v-if="donador.rfc" class="flex justify-between text-sm">
                            <span class="text-slate-500">RFC</span>
                            <span class="text-slate-700 font-mono text-xs">{{ donador.rfc }}</span>
                        </div>
                    </div>
                </div>

                <!-- Historial donaciones -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Historial de Donaciones</h3>
                    <div v-if="donador.donaciones?.length" class="divide-y divide-slate-100">
                        <div v-for="d in donador.donaciones" :key="d.id" class="py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ d.folio }}</p>
                                <p class="text-xs text-slate-400">{{ d.causa?.titulo ?? 'General' }} · {{ d.frecuencia }}</p>
                                <p class="text-xs text-slate-400">{{ fmtDate(d.fecha_pago) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-teal-600">{{ fmt(d.monto) }}</p>
                                <span :class="d.estado === 'completada' ? 'text-teal-600' : 'text-amber-600'"
                                    class="text-xs font-medium capitalize">{{ d.estado }}</span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-slate-400">No hay donaciones registradas.</p>
                </div>
            </div>

            <!-- Recibos -->
            <div v-if="donador.recibos?.length" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4">Recibos Fiscales</h3>
                <div class="divide-y divide-slate-100">
                    <div v-for="r in donador.recibos" :key="r.id" class="py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-700">{{ r.folio }}</p>
                            <p class="text-xs text-slate-400">{{ r.concepto }} · {{ fmtDate(r.fecha_emision) }}</p>
                        </div>
                        <span class="text-sm font-bold text-teal-600">{{ fmt(r.monto) }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-start">
                <Link href="/admin/donadores" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    ← Volver a Donadores
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
