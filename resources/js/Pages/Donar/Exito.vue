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
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-teal-500 to-emerald-600 text-4xl shadow-lg shadow-teal-500/20">
                ❤️
            </div>
            <h1 class="font-serif text-2xl font-bold text-slate-900 tracking-tight">¡Gracias por tu donación!</h1>
            <p class="text-sm text-slate-500">Tu generosidad hace posible el trabajo de AJDUT Mexico.</p>

            <div v-if="donacion" class="rounded-xl bg-slate-50 p-4 text-left space-y-2 mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Folio</span>
                    <span class="font-mono font-semibold text-slate-700">{{ donacion.folio }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Monto</span>
                    <span class="font-bold text-teal-600">{{ fmt(donacion.monto) }}</span>
                </div>
                <div v-if="donacion.causa" class="flex justify-between text-sm">
                    <span class="text-slate-500">Causa</span>
                    <span class="text-slate-700">{{ donacion.causa.titulo }}</span>
                </div>
            </div>

            <div v-if="donacion?.firma_electronica" class="rounded-xl border border-teal-100 bg-teal-50/50 p-4 text-left mt-3">
                <p class="text-xs font-bold text-teal-800 flex items-center gap-1.5 mb-2">📜 Carta de autorización de cargo firmada</p>
                <img :src="donacion.firma_electronica" alt="Firma" class="h-16 rounded-lg bg-white border border-teal-100 mb-2" />
                <p class="text-xs text-slate-500">{{ donacion.firma_nombre }}</p>
            </div>

            <div class="pt-4 space-y-3">
                <p class="text-xs text-slate-400">Recibirás un correo de confirmación con tu comprobante.</p>
                <Link href="/donar" class="block w-full rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-3 text-center text-sm font-bold text-white shadow hover:from-teal-700">
                    Hacer otra donación
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
