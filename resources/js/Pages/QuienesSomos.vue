<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({ equipo: Array })

const compromisos = [
    { titulo: 'Transparencia', desc: 'Rendimos cuentas claras y nos aseguramos de que cada apoyo llegue a quien más lo necesita.', path: 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' },
    { titulo: 'Responsabilidad', desc: 'Administramos cada donativo con cuidado, constancia y absoluto respeto por cada familia.', path: 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z' },
    { titulo: 'Sensibilidad', desc: 'Acompañamos a cada familia con calidez y respeto absoluto por su privacidad y dignidad.', path: 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z' },
]
</script>

<template>
    <Head title="Quiénes Somos — AJDUT México" />
    <PublicLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-900 via-teal-900 to-teal-800 text-white py-16">
            <div class="pointer-events-none absolute -top-16 -right-10 h-64 w-64 rounded-full bg-emerald-500/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-10 -left-10 h-56 w-56 rounded-full bg-coral-500/10 blur-3xl"></div>
            <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
                <span class="font-accent text-2xl text-emerald-200">Quiénes somos</span>
                <h1 class="font-serif text-4xl sm:text-5xl font-bold mt-1 mb-4">Nadie debe enfrentar la vida solo</h1>
                <p class="text-lg text-teal-50/90 max-w-2xl mx-auto leading-relaxed">
                    Ajdut nace con la convicción de que ninguna viuda ni ningún huérfano deben enfrentar solos las dificultades de la vida.
                </p>
            </div>
        </section>

        <!-- Narrativa -->
        <section class="bg-[#faf6ee] py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="space-y-6 font-serif text-lg sm:text-xl leading-relaxed text-teal-900/90">
                    <p>Somos una organización dedicada a brindar <strong class="font-semibold text-teal-900">apoyo económico, acompañamiento y esperanza</strong> a familias que han perdido a su principal sostén. Gracias a la generosidad de nuestros donadores, llevamos ayuda de manera digna, constante y con absoluto respeto por la privacidad de cada familia.</p>
                    <p>Creemos que cada acto de bondad transforma vidas. Por eso trabajamos para crear una comunidad unida, donde cada persona que dona se convierte en un <strong class="font-semibold text-teal-900">verdadero socio</strong> en esta misión de ayudar.</p>
                    <p>Nuestro compromiso es actuar con transparencia, responsabilidad y sensibilidad, asegurándonos de que cada apoyo llegue a quienes más lo necesitan.</p>
                    <p>En Ajdut, no solo entregamos ayuda; ofrecemos <strong class="font-semibold text-teal-900">tranquilidad, esperanza</strong> y la certeza de que siempre hay una comunidad dispuesta a tender la mano.</p>
                </div>

                <blockquote class="mt-12 rounded-2xl border-l-4 border-coral-500 bg-white px-8 py-7 shadow-sm">
                    <p class="font-serif text-2xl sm:text-3xl font-semibold leading-snug text-teal-900">
                        Porque cuando nos unimos para ayudar, cambiamos el mundo de una familia a la vez.
                    </p>
                </blockquote>
            </div>
        </section>

        <!-- Nuestro compromiso -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="font-accent text-2xl text-coral-600">Con el corazón</span>
                    <h2 class="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-800">Nuestro compromiso</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="c in compromisos" :key="c.titulo"
                        class="rounded-2xl border border-[#eaddc4] bg-[#faf6ee] p-8 text-center transition hover:border-teal-300 hover:shadow-md">
                        <span class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-50 text-teal-700">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="c.path" />
                            </svg>
                        </span>
                        <h3 class="font-serif text-lg font-bold text-slate-800 mb-2">{{ c.titulo }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ c.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative overflow-hidden bg-gradient-to-br from-teal-900 via-teal-900 to-teal-800 py-16 text-center text-white">
            <div class="relative mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <p class="font-accent text-3xl text-emerald-200 mb-3">Sé parte de esta gran mitzvá</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold mb-8">Conviértete en socio de esta misión</h2>
                <Link href="/donar"
                    class="btn-pop inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-coral-500 to-coral-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-coral-900/30 hover:shadow-2xl transition">
                    ❤️ Quiero ayudar
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
