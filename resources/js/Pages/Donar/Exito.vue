<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({ donacion: Object });

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);
</script>

<template>
    <Head title="¡Gracias por tu donación! — AJDUT Mexico" />
    <GuestLayout>
        <div class="text-center space-y-4">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-coral-500 to-coral-600 text-4xl shadow-lg shadow-coral-500/30 animate-heartbeat">
                ❤️
            </div>
            <h1 class="font-serif text-2xl font-bold text-slate-900 tracking-tight">¡Gracias por tu donación!</h1>
            <p class="text-sm text-slate-500">Tu generosidad hace posible el trabajo de AJDUT Mexico.</p>
            <p class="font-accent text-xl text-emerald-600">Es una mitzvá que transforma vidas ✨</p>

            <div v-if="donacion" class="rounded-xl bg-slate-50 p-4 text-left space-y-2 mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Folio</span>
                    <span class="font-mono font-semibold text-slate-700">{{ donacion.folio }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Monto</span>
                    <span class="font-bold text-coral-600">{{ fmt(donacion.monto) }}</span>
                </div>
                <div v-if="donacion.causa" class="flex justify-between text-sm">
                    <span class="text-slate-500">Causa</span>
                    <span class="text-slate-700">{{ donacion.causa.titulo }}</span>
                </div>
            </div>

            <div v-if="donacion?.firma_electronica" class="rounded-xl border border-teal-100 bg-teal-50/50 p-4 text-left mt-3">
                <p class="text-xs font-bold text-teal-800 flex items-center gap-1.5 mb-2">📜 Carta de autorización de cargo firmada</p>
                <img :src="donacion.firma_electronica" alt="Firma" class="h-16 rounded-lg bg-white border border-teal-100 mb-3" />
                <p class="text-xs text-slate-500 mb-3">{{ donacion.firma_nombre }}</p>
                <a :href="`/donar/carta/${donacion.folio}`" target="_blank" rel="noopener"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-teal-600 py-2.5 text-sm font-semibold text-teal-700 transition hover:bg-teal-600 hover:text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v2.625a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3V14.25m10.5-3-3 3m0 0-3-3m3 3V4.5" /></svg>
                    Descargar carta de autorización (PDF)
                </a>
            </div>

            <div class="pt-4 space-y-3">
                <p class="text-xs text-slate-400">Recibirás un correo de confirmación con tu comprobante.</p>
                <Link href="/donar" class="btn-pop block w-full rounded-xl bg-gradient-to-r from-coral-500 to-coral-600 px-4 py-3 text-center text-sm font-bold text-white shadow-lg shadow-coral-500/20 hover:from-coral-600">
                    Hacer otra donación
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
