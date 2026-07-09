<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({ campanas: { type: Array, default: () => [] } })

const fmt = (n) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(n ?? 0)
const pct = (r, m) => (m > 0 ? Math.min(100, Math.round((r / m) * 100)) : 0)

// Group the campaigns by festividad (jag)
const grupos = computed(() => {
    const map = {}
    for (const c of props.campanas) {
        const key = c.jag || 'Festividades'
        if (!map[key]) map[key] = []
        map[key].push(c)
    }
    return Object.entries(map).map(([jag, items]) => ({ jag, items }))
})
</script>

<template>
    <Head title="Jaguim — Campañas por festividad · AJDUT México" />
    <PublicLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-coral-700 text-white py-16">
            <div class="pointer-events-none absolute -top-16 -right-10 h-64 w-64 rounded-full bg-coral-400/20 blur-3xl animate-float-slow"></div>
            <div class="pointer-events-none absolute -bottom-10 -left-10 h-56 w-56 rounded-full bg-emerald-300/20 blur-3xl animate-float-slow" style="animation-delay:2s"></div>
            <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <span class="font-accent text-2xl text-coral-200">Jaguim · Festividades</span>
                <h1 class="font-serif text-4xl sm:text-5xl font-extrabold mt-1 mb-4">Campañas por festividad</h1>
                <p class="text-lg text-teal-50/90 max-w-2xl mx-auto">Acompaña a nuestra comunidad en cada jag. Elige una festividad y haz tu donativo con propósito. 🙌</p>
            </div>
        </section>

        <section class="py-14 bg-slate-50">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div v-if="!campanas.length" class="rounded-2xl border border-dashed border-slate-300 bg-white py-16 text-center text-slate-500">
                    Pronto publicaremos las campañas de las próximas festividades. 🙌
                </div>

                <div v-for="grupo in grupos" :key="grupo.jag" class="mb-14">
                    <div class="mb-6 flex items-center gap-3">
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-teal-800">{{ grupo.jag }}</h2>
                        <span class="h-px flex-1 bg-gradient-to-r from-coral-300/60 to-transparent"></span>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <article v-for="c in grupo.items" :key="c.id" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg">
                            <div class="flex h-40 items-center justify-center overflow-hidden bg-gradient-to-br from-teal-600 to-coral-500">
                                <img v-if="c.imagen" :src="c.imagen" :alt="c.titulo" class="h-full w-full object-cover" />
                                <span v-else class="font-accent text-3xl text-white/90">{{ c.jag }}</span>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <h3 class="font-serif text-lg font-bold text-slate-800">{{ c.titulo }}</h3>
                                <p class="mt-1 flex-1 text-sm text-slate-500">{{ c.descripcion_corta }}</p>

                                <div v-if="c.meta_recaudacion > 0" class="mt-4">
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-gradient-to-r from-teal-500 to-coral-500" :style="{ width: pct(c.recaudado, c.meta_recaudacion) + '%' }"></div>
                                    </div>
                                    <div class="mt-1 flex justify-between text-xs text-slate-400">
                                        <span>{{ fmt(c.recaudado) }} recaudado</span>
                                        <span>Meta {{ fmt(c.meta_recaudacion) }}</span>
                                    </div>
                                </div>

                                <Link :href="`/donar?causa=${c.id}`" class="btn-pop mt-4 inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-coral-500 to-coral-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-coral-900/20 transition hover:from-coral-400 hover:to-coral-500">
                                    Donar para {{ c.jag }}
                                </Link>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
