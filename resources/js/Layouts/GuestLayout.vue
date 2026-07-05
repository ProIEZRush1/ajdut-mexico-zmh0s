<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-slate-900 px-4 py-10">
        <!-- Ambient teal glows -->
        <div class="pointer-events-none absolute -top-32 -left-32 h-96 w-96 rounded-full bg-teal-600 opacity-20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 -right-24 h-[28rem] w-[28rem] rounded-full bg-emerald-500 opacity-20 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(13,148,136,0.15),_transparent_55%)]"></div>

        <div class="relative z-10 w-full max-w-md">
            <div class="mb-8 flex flex-col items-center text-center">
                <Link href="/" class="transition hover:opacity-90">
                    <ApplicationLogo mark-size="h-16 w-16" text-size="text-2xl" />
                </Link>
                <p class="mt-3 text-sm text-slate-400">Plataforma de Donaciones</p>
            </div>

            <div class="w-full rounded-2xl border border-emerald-500/20 bg-white/96 p-8 shadow-2xl shadow-teal-950/40 backdrop-blur-sm sm:p-10">
                <slot />
            </div>

            <p class="mt-8 text-center text-xs text-teal-300/70">
                AJDUT México &middot; Desarrollado por
                <span class="font-semibold text-teal-200/80">Overcloud</span>
            </p>
        </div>
    </div>
</template>
