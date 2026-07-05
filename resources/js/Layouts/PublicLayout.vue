<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useI18n } from '@/composables/useI18n'

const { t, lang, setLang } = useI18n()
const page = usePage()
const menuOpen = ref(false)
const waLink = 'https://wa.me/5215594356241'

const isActive = (path) => {
    const url = (page.url || '').split('?')[0]
    if (path === '/') return url === '/'
    return url === path || url.startsWith(path + '/')
}

const navLinks = computed(() => [
    { href: '/', label: t('nav.home') },
    { href: '/quienes-somos', label: t('nav.about') },
    { href: '/causas-publicas', label: t('nav.causes') },
    { href: '/planes-donacion', label: t('nav.plans') },
    { href: '/transparencia-publica', label: t('nav.transparency') },
    { href: '/noticias-blog', label: t('nav.news') },
    { href: '/contacto', label: t('nav.contact') },
])
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-emerald-200/60 shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between py-3">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-3 min-w-0 flex-shrink-0">
                        <img src="/brand-logo.jpeg" alt="AJDUT México" class="h-12 w-12 rounded-xl object-contain shadow-sm border border-slate-100" />
                        <div class="hidden sm:block">
                            <span class="block font-serif text-lg font-bold text-teal-800 leading-tight tracking-tight">AJDUT México</span>
                            <span class="block text-[11px] text-emerald-700 font-semibold uppercase tracking-wider">Plataforma de Donaciones</span>
                        </div>
                    </Link>

                    <!-- Desktop nav -->
                    <nav class="hidden lg:flex items-center gap-1">
                        <Link v-for="link in navLinks" :key="link.href" :href="link.href"
                            :class="isActive(link.href)
                                ? 'text-teal-700 bg-teal-50 font-semibold'
                                : 'text-slate-600 hover:text-teal-700 hover:bg-teal-50 font-medium'"
                            class="px-3 py-2 rounded-lg text-sm transition-colors whitespace-nowrap">
                            {{ link.label }}
                        </Link>
                    </nav>

                    <!-- Right actions -->
                    <div class="flex items-center gap-2">
                        <!-- Lang selector -->
                        <div class="hidden sm:flex items-center gap-1 rounded-lg border border-slate-200 p-0.5">
                            <button @click="setLang('es')"
                                :class="lang === 'es' ? 'bg-teal-600 text-white' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2.5 py-1 rounded-md text-xs font-bold transition">ES</button>
                            <button @click="setLang('en')"
                                :class="lang === 'en' ? 'bg-teal-600 text-white' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2.5 py-1 rounded-md text-xs font-bold transition">EN</button>
                        </div>

                        <!-- Portal / login -->
                        <Link v-if="$page.props.auth?.user" href="/portal"
                            class="hidden sm:flex items-center gap-1.5 text-sm font-semibold text-teal-700 hover:text-teal-800 px-3 py-2 rounded-lg hover:bg-teal-50 transition">
                            {{ t('nav.portal') }}
                        </Link>
                        <Link v-else href="/login"
                            class="hidden sm:flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50 transition">
                            {{ t('nav.login') }}
                        </Link>

                        <!-- Donate CTA -->
                        <Link href="/donar"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-teal-500/20 hover:from-teal-700 transition">
                            ❤️ {{ t('nav.donate') }}
                        </Link>

                        <!-- Mobile hamburger -->
                        <button @click="menuOpen = !menuOpen"
                            class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path v-if="!menuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div v-show="menuOpen" class="lg:hidden border-t border-slate-100 py-3 space-y-1">
                    <Link v-for="link in navLinks" :key="link.href" :href="link.href"
                        @click="menuOpen = false"
                        :class="isActive(link.href) ? 'text-teal-700 bg-teal-50 font-semibold' : 'text-slate-600 font-medium'"
                        class="block px-4 py-2.5 rounded-lg text-sm transition">
                        {{ link.label }}
                    </Link>
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between px-4">
                        <div class="flex items-center gap-1">
                            <button @click="setLang('es')" :class="lang === 'es' ? 'bg-teal-600 text-white' : 'text-slate-500'" class="px-2.5 py-1 rounded-md text-xs font-bold border border-slate-200 transition">ES</button>
                            <button @click="setLang('en')" :class="lang === 'en' ? 'bg-teal-600 text-white' : 'text-slate-500'" class="px-2.5 py-1 rounded-md text-xs font-bold border border-slate-200 transition">EN</button>
                        </div>
                        <Link v-if="$page.props.auth?.user" href="/portal" class="text-sm font-semibold text-teal-700">{{ t('nav.portal') }}</Link>
                        <Link v-else href="/login" class="text-sm font-semibold text-slate-600">{{ t('nav.login') }}</Link>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-teal-900 text-teal-100 border-t-2 border-emerald-500/40">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
                <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="/brand-logo.jpeg" alt="AJDUT México" class="h-10 w-10 rounded-xl object-contain bg-white/10 p-0.5" />
                            <span class="font-serif text-white font-bold text-lg tracking-tight">AJDUT México</span>
                        </div>
                        <p class="text-sm text-teal-200/80 leading-relaxed">
                            Institución de donaciones comprometida con el bienestar social de México. Transparencia total, impacto real.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Navegación</h4>
                        <ul class="space-y-2">
                            <li v-for="link in navLinks" :key="link.href">
                                <Link :href="link.href" class="text-sm text-teal-200/80 hover:text-emerald-400 transition">{{ link.label }}</Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Donar</h4>
                        <Link href="/donar" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-teal-950 hover:bg-emerald-400 transition mb-4">
                            ❤️ {{ t('nav.donate') }}
                        </Link>
                        <p class="text-xs text-teal-300/70 mt-2">Pagos seguros procesados con Stripe.</p>
                    </div>
                </div>
                <div class="mt-10 pt-8 border-t border-teal-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-teal-300/70">
                    <p>&copy; {{ new Date().getFullYear() }} AJDUT México. {{ t('footer.rights') }}</p>
                    <p>
                        Desarrollado por
                        <a :href="'https://wa.me/5215594356241'" target="_blank" rel="noopener" class="text-emerald-400 font-semibold hover:text-emerald-300">Overcloud</a>
                        ·
                        <a :href="'https://wa.me/5215594356241'" target="_blank" rel="noopener" class="hover:text-teal-100 transition">{{ t('footer.cta') }}</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
