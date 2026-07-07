<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

const form = useForm({
    nombre: '',
    email: '',
    telefono: '',
    asunto: '',
    mensaje: '',
});

const submit = () => form.post('/contacto', { preserveScroll: true });
</script>

<template>
    <Head title="Contacto — AJDUT Mexico" />
    <GuestLayout>
        <div class="mb-6 text-center">
            <span class="text-3xl block mb-2">💌</span>
            <h1 class="font-serif text-2xl font-bold text-slate-900">Contáctanos</h1>
            <p class="mt-1 text-sm text-slate-500">Estamos aquí para escucharte y ayudarte.</p>
        </div>

        <div v-if="page.props.flash?.success" class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ page.props.flash.success }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nombre *</label>
                    <input v-model="form.nombre" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email *</label>
                    <input v-model="form.email" type="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Teléfono</label>
                <input v-model="form.telefono" type="tel" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Asunto *</label>
                <input v-model="form.asunto" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required />
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Mensaje *</label>
                <textarea v-model="form.mensaje" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-coral-400 focus:outline-none" required></textarea>
            </div>
            <button type="submit" :disabled="form.processing"
                class="btn-pop w-full rounded-xl bg-gradient-to-r from-coral-500 to-coral-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-coral-500/20 hover:from-coral-600 disabled:opacity-50">
                {{ form.processing ? 'Enviando...' : '💌 Enviar mensaje' }}
            </button>
        </form>
    </GuestLayout>
</template>
