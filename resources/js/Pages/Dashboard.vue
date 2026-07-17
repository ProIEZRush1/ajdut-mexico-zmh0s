<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    causas_recientes: Array,
    donaciones_recientes: Array,
});

const page = usePage();
const businessName = computed(() => page.props.name ?? 'AJDUT Mexico');
const userFirstName = computed(() => {
    const name = (page.props.auth?.user?.name ?? '').trim();
    return name ? name.split(/\s+/)[0] : '';
});

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0);
</script>

<template>
    <Head title="Panel de Control" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold tracking-tight text-slate-800">Panel de Control</h2>
        </template>

        <div class="mx-auto max-w-7xl space-y-8">
            <!-- Hero -->
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-700 p-8 text-white shadow-xl shadow-teal-500/20 sm:p-10">
                <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-emerald-300/20 blur-2xl"></div>
                <div class="relative flex items-center gap-6">
                    <img src="/logo-ajdut.jpg" alt="AJDUT Mexico" class="hidden h-20 w-20 rounded-2xl object-contain shadow-lg sm:block bg-white/10 p-1" />
                    <div>
                        <p class="text-sm font-medium uppercase tracking-widest text-white/70">Apoyo a viudas y huérfanos de México</p>
                        <h1 class="mt-2 text-3xl font-extrabold leading-tight sm:text-4xl">
                            Hola<span v-if="userFirstName">, {{ userFirstName }}</span> 👋
                        </h1>
                        <p class="mt-2 max-w-2xl text-base text-white/85">
                            Bienvenido al panel de administración de <span class="font-semibold">{{ businessName }}</span>.
                            Gestiona causas, donadores y transparencia desde aquí.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Stats grid -->
            <section>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-md shadow-teal-500/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-extrabold text-slate-800">{{ stats?.donadores ?? 0 }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-600">Donadores Activos</p>
                        <p class="mt-0.5 text-xs text-slate-400">Registrados en la plataforma</p>
                    </div>

                    <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-md shadow-amber-500/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-extrabold text-slate-800">{{ fmt(stats?.total_recaudado) }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-600">Total Recaudado</p>
                        <p class="mt-0.5 text-xs text-slate-400">Donaciones completadas</p>
                    </div>

                    <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 text-white shadow-md shadow-teal-500/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-extrabold text-slate-800">{{ stats?.causas_activas ?? 0 }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-600">Causas Activas</p>
                        <p class="mt-0.5 text-xs text-slate-400">Programas en curso</p>
                    </div>

                    <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-extrabold text-slate-800">{{ stats?.donaciones_mes ?? 0 }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-600">Donaciones este Mes</p>
                        <p class="mt-0.5 text-xs text-slate-400">Transacciones completadas</p>
                    </div>
                </div>
            </section>

            <!-- Content grid -->
            <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Causas recientes -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-800">Causas Activas</h3>
                        <Link href="/admin/causas" class="text-sm font-medium text-teal-600 hover:text-teal-700">Ver todas →</Link>
                    </div>
                    <div v-if="causas_recientes && causas_recientes.length" class="space-y-4">
                        <div v-for="causa in causas_recientes" :key="causa.id">
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700 truncate flex-1 mr-2">{{ causa.titulo }}</span>
                                <span class="text-teal-600 font-semibold flex-shrink-0">
                                    {{ Math.min(100, Math.round((causa.recaudado / (causa.meta_recaudacion || 1)) * 100)) }}%
                                </span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div
                                    class="h-2 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 transition-all"
                                    :style="{ width: Math.min(100, Math.round((causa.recaudado / (causa.meta_recaudacion || 1)) * 100)) + '%' }"
                                ></div>
                            </div>
                            <div class="flex justify-between text-xs text-slate-400 mt-1">
                                <span>{{ fmt(causa.recaudado) }} recaudados</span>
                                <span>Meta: {{ fmt(causa.meta_recaudacion) }}</span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-slate-400">No hay causas activas aún.</p>
                </div>

                <!-- Últimas donaciones -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-800">Últimas Donaciones</h3>
                        <Link href="/admin/donaciones" class="text-sm font-medium text-teal-600 hover:text-teal-700">Ver todas →</Link>
                    </div>
                    <div v-if="donaciones_recientes && donaciones_recientes.length" class="divide-y divide-slate-100">
                        <div v-for="d in donaciones_recientes" :key="d.id" class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ d.donador?.nombre ?? 'Anónimo' }} {{ d.donador?.apellido ?? '' }}</p>
                                <p class="text-xs text-slate-400">{{ d.folio }} · {{ d.frecuencia }}</p>
                            </div>
                            <span class="text-sm font-bold text-teal-600">{{ fmt(d.monto) }}</span>
                        </div>
                    </div>
                    <p v-else class="text-sm text-slate-400">No hay donaciones recientes.</p>
                </div>
            </section>

            <!-- Quick access -->
            <section>
                <h3 class="text-base font-bold text-slate-800 mb-4">Acceso Rápido</h3>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    <Link v-for="item in [
                        { href: '/admin/causas/crear', label: 'Nueva Causa', icon: '❤️' },
                        { href: '/admin/donaciones/crear', label: 'Registrar Donación', icon: '💳' },
                        { href: '/admin/noticias/crear', label: 'Nueva Noticia', icon: '📰' },
                        { href: '/admin/mensajes', label: `Mensajes${stats?.mensajes_nuevos ? ' (' + stats.mensajes_nuevos + ')' : ''}`, icon: '✉️' },
                    ]" :key="item.href" :href="item.href"
                        class="flex flex-col items-center gap-2 rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm transition hover:border-teal-300 hover:shadow-md"
                    >
                        <span class="text-2xl">{{ item.icon }}</span>
                        <span class="text-xs font-semibold text-slate-600">{{ item.label }}</span>
                    </Link>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
