<script setup>
import { ref, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();

const trialLocked = computed(() => page.props.trial_locked ?? false);
const businessName = computed(() => page.props.name ?? 'AJDUT Mexico');
const userName = computed(() => page.props.auth?.user?.name ?? '');
const userEmail = computed(() => page.props.auth?.user?.email ?? '');

const capabilities = computed(() =>
    Array.isArray(page.props.capabilities) ? page.props.capabilities : [],
);

const isCapabilityActive = (href) => {
    if (!href) return false;
    const current = (page.url || '').split('?')[0];
    const target = href.split('?')[0];
    return current === target || current.startsWith(target + '/');
};

const userInitials = computed(() => {
    const name = (userName.value || '').trim();
    if (!name) return '?';
    const parts = name.split(/\s+/).filter(Boolean);
    return parts.length === 1
        ? parts[0].substring(0, 2).toUpperCase()
        : (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
});
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Trial banner -->
        <div
            v-if="trialLocked"
            class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center gap-2 bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm"
        >
            🔒 Versión de prueba — activa todo con tu anticipo.
        </div>

        <!-- Sidebar (desktop) -->
        <aside
            :class="trialLocked ? 'top-9' : 'top-0'"
            class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-slate-200 bg-white lg:flex"
        >
            <!-- Brand -->
            <div class="flex h-20 items-center gap-3 px-5 border-b border-slate-100">
                <Link :href="route('dashboard')" class="flex items-center gap-3 min-w-0">
                    <img src="/logo-ajdut.jpg" alt="AJDUT Mexico" class="h-10 w-10 rounded-lg object-contain flex-shrink-0" />
                    <span class="text-base font-extrabold tracking-tight leading-tight text-teal-700 truncate">
                        {{ businessName }}
                    </span>
                </Link>
            </div>

            <!-- Nav -->
            <nav class="flex-1 space-y-0.5 px-3 py-4 overflow-y-auto">
                <Link
                    :href="route('dashboard')"
                    :class="[
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition',
                        route().current('dashboard')
                            ? 'bg-teal-600 text-white shadow-md shadow-teal-500/20'
                            : 'text-slate-600 hover:bg-teal-50 hover:text-teal-700',
                    ]"
                >
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Panel de Control
                </Link>

                <Link
                    v-for="item in capabilities"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition',
                        isCapabilityActive(item.href)
                            ? 'bg-teal-600 text-white shadow-md shadow-teal-500/20'
                            : 'text-slate-600 hover:bg-teal-50 hover:text-teal-700',
                    ]"
                >
                    <span class="flex h-4 w-4 items-center justify-center text-sm leading-none flex-shrink-0">
                        {{ item.icon }}
                    </span>
                    {{ item.label }}
                </Link>
            </nav>

            <div class="px-5 py-4 border-t border-slate-100">
                <p class="text-xs text-slate-400">Desarrollado por <span class="font-semibold text-slate-500">Overcloud</span></p>
            </div>
        </aside>

        <!-- Main column -->
        <div :class="['lg:pl-64', trialLocked ? 'pt-9' : '']">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8">
                <!-- Mobile hamburger + brand -->
                <div class="flex flex-1 items-center gap-3 lg:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <Link :href="route('dashboard')" class="flex items-center gap-2">
                        <img src="/logo-ajdut.jpg" alt="AJDUT Mexico" class="h-8 w-8 rounded-lg object-contain" />
                        <span class="text-base font-extrabold text-teal-700">{{ businessName }}</span>
                    </Link>
                </div>

                <div class="hidden min-w-0 flex-1 lg:block">
                    <slot name="header" />
                </div>

                <!-- User dropdown -->
                <div class="relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white py-1 pl-1 pr-3 text-sm font-medium text-slate-600 transition hover:border-teal-300 hover:text-teal-700">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-teal-500 to-emerald-600 text-xs font-bold text-white">
                                    {{ userInitials }}
                                </span>
                                <span class="hidden sm:inline">{{ userName }}</span>
                                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>
                        <template #content>
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="text-sm font-semibold text-slate-800">{{ userName }}</p>
                                <p class="truncate text-xs text-slate-500">{{ userEmail }}</p>
                            </div>
                            <DropdownLink :href="route('profile.edit')">Mi perfil</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Cerrar sesión</DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Mobile nav -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="border-b border-slate-200 bg-white lg:hidden">
                <div class="space-y-1 px-4 py-3">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Panel de Control
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-for="item in capabilities" :key="item.href" :href="item.href" :active="isCapabilityActive(item.href)">
                        <span class="mr-2">{{ item.icon }}</span>{{ item.label }}
                    </ResponsiveNavLink>
                </div>
                <div class="border-t border-slate-200 px-4 py-4">
                    <p class="text-base font-semibold text-slate-800">{{ userName }}</p>
                    <p class="text-sm text-slate-500">{{ userEmail }}</p>
                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">Mi perfil</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Cerrar sesión</ResponsiveNavLink>
                    </div>
                </div>
            </div>

            <div v-if="$slots.header" class="border-b border-slate-200 bg-white px-4 py-5 sm:px-6 lg:hidden">
                <slot name="header" />
            </div>

            <main class="px-4 py-8 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
