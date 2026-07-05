<script setup>
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { useI18n } from '@/composables/useI18n'

defineProps({ equipo: Array })
const { t } = useI18n()

const valores = [
    { icon: '❤️', titulo: 'Solidaridad', desc: 'Creemos en la fuerza de la comunidad unida por un bien común.' },
    { icon: '🔍', titulo: 'Transparencia', desc: 'Rendimos cuentas claras a nuestros donadores en todo momento.' },
    { icon: '💡', titulo: 'Innovación Social', desc: 'Buscamos soluciones creativas a los problemas más urgentes.' },
    { icon: '🤝', titulo: 'Compromiso', desc: 'Nos comprometemos con el bienestar de cada persona que apoyamos.' },
    { icon: '⚖️', titulo: 'Justicia', desc: 'Trabajamos por una sociedad más equitativa para todos.' },
    { icon: '🌱', titulo: 'Sustentabilidad', desc: 'Generamos impacto duradero pensando en las generaciones futuras.' },
]

const staticEquipo = [
    { nombre: 'Lic. Patricia Morales', cargo: 'Directora General', descripcion: 'Más de 20 años de trayectoria en organizaciones de impacto social.' },
    { nombre: 'Dr. Alejandro Ríos', cargo: 'Director de Programas', descripcion: 'Especialista en desarrollo comunitario y diseño de intervenciones sociales.' },
    { nombre: 'Lic. Sofía Vega', cargo: 'Directora de Transparencia', descripcion: 'Experta en gobernanza y rendición de cuentas para organizaciones civiles.' },
    { nombre: 'Ing. Marco Torres', cargo: 'Director de Tecnología', descripcion: 'Lidera la transformación digital de nuestra plataforma de donaciones.' },
]
</script>

<template>
    <Head title="Quiénes Somos — AJDUT México" />
    <PublicLayout>
        <!-- Hero -->
        <section class="bg-gradient-to-br from-teal-700 to-emerald-600 text-white py-16">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">{{ t('about.title') }}</h1>
                <p class="text-xl text-white/85 max-w-2xl mx-auto">{{ t('about.subtitle') }}</p>
            </div>
        </section>

        <!-- Historia -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 items-center">
                    <div>
                        <span class="text-sm font-bold text-teal-600 uppercase tracking-wider">Desde 2010</span>
                        <h2 class="mt-2 text-3xl font-extrabold text-slate-800 mb-6">{{ t('about.history.title') }}</h2>
                        <div class="space-y-4 text-slate-600 leading-relaxed">
                            <p>AJDUT México nació en 2010 como respuesta a la creciente brecha de desigualdad en comunidades rurales y urbanas marginadas de nuestro país. Un grupo de profesionistas y ciudadanos comprometidos decidieron crear una institución transparente y eficiente que canalizara recursos hacia quienes más los necesitan.</p>
                            <p>Desde nuestra fundación, hemos apoyado a más de 5,000 familias a través de programas de alimentación, educación, salud y desarrollo comunitario. Cada peso que nos confían es administrado con absoluta honestidad y publicado en nuestros reportes de transparencia.</p>
                            <p>Hoy, AJDUT México es una institución reconocida por su rigor en la rendición de cuentas, su impacto medible y su profundo compromiso con la dignidad humana.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-teal-500 to-emerald-600 rounded-2xl p-6 text-white text-center shadow-lg">
                            <p class="text-4xl font-extrabold">15+</p>
                            <p class="text-sm mt-1 text-white/80">años de experiencia</p>
                        </div>
                        <div class="bg-slate-800 rounded-2xl p-6 text-white text-center shadow-lg">
                            <p class="text-4xl font-extrabold">5K+</p>
                            <p class="text-sm mt-1 text-slate-400">familias beneficiadas</p>
                        </div>
                        <div class="bg-amber-500 rounded-2xl p-6 text-white text-center shadow-lg">
                            <p class="text-4xl font-extrabold">18</p>
                            <p class="text-sm mt-1 text-amber-100">causas exitosas</p>
                        </div>
                        <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl p-6 text-white text-center shadow-lg">
                            <p class="text-4xl font-extrabold">100%</p>
                            <p class="text-sm mt-1 text-violet-100">transparencia</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Misión y Visión -->
        <section class="py-20 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-3xl mb-6 shadow-md shadow-teal-500/20">🎯</div>
                        <h2 class="text-2xl font-extrabold text-slate-800 mb-4">{{ t('about.mission.title') }}</h2>
                        <p class="text-slate-600 leading-relaxed">Canalizar recursos de donadores comprometidos hacia causas de alto impacto social, garantizando transparencia absoluta, eficiencia en la administración y resultados medibles que transformen vidas en las comunidades más vulnerables de México.</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-3xl mb-6 shadow-md shadow-amber-500/20">🔭</div>
                        <h2 class="text-2xl font-extrabold text-slate-800 mb-4">{{ t('about.vision.title') }}</h2>
                        <p class="text-slate-600 leading-relaxed">Ser la plataforma de donaciones más confiable e innovadora de México, donde cada ciudadano pueda contribuir fácilmente a construir un país más justo, y donde cada peso donado tenga un impacto verificable y duradero.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Valores -->
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-slate-800">{{ t('about.values.title') }}</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="v in valores" :key="v.titulo"
                        class="flex gap-4 p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-teal-200 transition">
                        <span class="text-3xl flex-shrink-0">{{ v.icon }}</span>
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">{{ v.titulo }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ v.desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Equipo -->
        <section class="py-20 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-slate-800">{{ t('about.team.title') }}</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="m in (equipo?.length ? equipo : staticEquipo)" :key="m.nombre ?? m.id"
                        class="bg-white rounded-2xl border border-slate-200 p-6 text-center shadow-sm hover:shadow-lg transition">
                        <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-3xl font-bold text-white mx-auto mb-4 shadow-md shadow-teal-500/20">
                            {{ (m.nombre ?? 'T')[0] }}
                        </div>
                        <h3 class="font-bold text-slate-800">{{ m.nombre }}</h3>
                        <p class="text-sm font-semibold text-teal-600 mt-0.5 mb-2">{{ m.cargo ?? m.puesto }}</p>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ m.descripcion ?? m.bio }}</p>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
