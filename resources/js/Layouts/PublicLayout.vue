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
    { href: '/#como-ayudar', label: 'Cómo ayudar' },
    { href: '/jaguim', label: 'Jaguim' },
    { href: '/transparencia-publica', label: t('nav.transparency') },
    { href: '/contacto', label: t('nav.contact') },
])
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-coral-100/70 shadow-sm">
            <div class="h-1 w-full bg-gradient-to-r from-coral-500 via-emerald-400 to-teal-600"></div>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between py-3">
                    <!-- Logo -->
                    <Link href="/" class="group flex items-center gap-3 min-w-0 flex-shrink-0">
                        <img src="/brand-logo.svg" alt="AJDUT México" class="h-12 w-12 rounded-2xl object-contain shadow-md border border-slate-100 transition duration-300 group-hover:rotate-3 group-hover:scale-105" />
                        <div class="hidden sm:block">
                            <span class="block font-serif text-lg font-bold text-teal-800 leading-tight tracking-tight">AJDUT México</span>
                            <span class="block text-[11px] text-coral-600 font-semibold uppercase tracking-wider">Plataforma de Donaciones</span>
                        </div>
                    </Link>

                    <!-- Desktop nav -->
                    <nav class="hidden lg:flex items-center gap-1">
                        <Link v-for="link in navLinks" :key="link.href" :href="link.href"
                            :class="isActive(link.href)
                                ? 'text-teal-700 font-bold'
                                : 'text-slate-600 hover:text-teal-700 font-medium'"
                            class="relative px-3 py-2 text-sm transition-colors whitespace-nowrap group">
                            {{ link.label }}
                            <span
                                :class="isActive(link.href) ? 'scale-x-100 bg-coral-500' : 'scale-x-0 bg-teal-400 group-hover:scale-x-100'"
                                class="absolute left-3 right-3 -bottom-0.5 h-0.5 rounded-full origin-center transition-transform duration-300"></span>
                        </Link>
                    </nav>

                    <!-- Right actions -->
                    <div class="flex items-center gap-2">
                        <!-- Lang selector -->
                        <div class="hidden sm:flex items-center gap-0.5 rounded-full border border-slate-200 p-0.5 bg-slate-50">
                            <button @click="setLang('es')"
                                :class="lang === 'es' ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2.5 py-1 rounded-full text-xs font-bold transition">🇲🇽 ES</button>
                            <button @click="setLang('en')"
                                :class="lang === 'en' ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2.5 py-1 rounded-full text-xs font-bold transition">🇺🇸 EN</button>
                        </div>

                        <!-- Portal / login -->
                        <Link v-if="$page.props.auth?.user" href="/portal"
                            class="hidden sm:flex items-center gap-1.5 text-sm font-semibold text-teal-700 hover:text-teal-800 px-3 py-2 rounded-full hover:bg-teal-50 transition">
                            {{ t('nav.portal') }}
                        </Link>
                        <Link v-else href="/login"
                            class="hidden sm:flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-teal-700 px-3 py-2 rounded-full hover:bg-teal-50 transition">
                            {{ t('nav.login') }}
                        </Link>

                        <!-- Donate CTA -->
                        <Link href="/donar"
                            class="btn-pop group inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-coral-500 to-coral-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-coral-500/30 hover:shadow-lg hover:shadow-coral-500/40 transition">
                            <span class="inline-block group-hover:animate-heartbeat">❤️</span> {{ t('nav.donate') }}
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
        <footer class="relative overflow-hidden bg-gradient-to-br from-teal-900 via-teal-900 to-coral-900/80 text-teal-100 border-t-2 border-coral-500/40">
            <div class="pointer-events-none absolute -top-16 -right-16 h-72 w-72 rounded-full bg-coral-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
                <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="/brand-logo.svg" alt="AJDUT México" class="h-10 w-10 rounded-xl object-contain bg-white/10 p-0.5" />
                            <span class="font-serif text-white font-bold text-lg tracking-tight">AJDUT México</span>
                        </div>
                        <p class="text-sm text-teal-200/80 leading-relaxed">
                            Institución de donaciones comprometida con el bienestar social de México. Transparencia total, impacto real.
                        </p>
                        <p class="mt-3 font-accent text-2xl text-emerald-300/90">Cada aportación es una mitzvá ✨</p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Navegación</h4>
                        <ul class="space-y-2">
                            <li v-for="link in navLinks" :key="link.href">
                                <Link :href="link.href" class="text-sm text-teal-200/80 hover:text-coral-300 transition">{{ link.label }}</Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Donar</h4>
                        <Link href="/donar" class="btn-pop inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-coral-500 to-coral-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-coral-900/30 hover:from-coral-400 hover:to-coral-500 transition mb-4">
                            ❤️ {{ t('nav.donate') }}
                        </Link>
                        <p class="text-xs text-teal-300/70 mt-2">Pagos seguros procesados con Stripe.</p>
                    </div>
                </div>
                <div class="mt-10 pt-8 border-t border-teal-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-teal-300/70">
                    <p>&copy; {{ new Date().getFullYear() }} AJDUT México. {{ t('footer.rights') }}</p>
                    <p>
                        Desarrollado por
                        <a :href="'https://wa.me/5215594356241'" target="_blank" rel="noopener" class="text-coral-300 font-semibold hover:text-coral-200">Overcloud</a>
                        ·
                        <a :href="'https://wa.me/5215594356241'" target="_blank" rel="noopener" class="hover:text-teal-100 transition">{{ t('footer.cta') }}</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
