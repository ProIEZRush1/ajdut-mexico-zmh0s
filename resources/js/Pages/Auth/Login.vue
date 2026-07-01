<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({ email: '', password: '', remember: false });

const submit = () => {
    form.post(route('login'), { onFinish: () => form.reset('password') });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar sesión — AJDUT Mexico" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900">Bienvenido a AJDUT Mexico</h1>
            <p class="mt-1 text-sm text-slate-500">Inicia sesión para administrar la plataforma de donaciones.</p>
        </div>

        <div v-if="status" class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="email" value="Correo electrónico" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"
                    required autofocus autocomplete="username" placeholder="tu@correo.com" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Contraseña" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                    required autocomplete="current-password" placeholder="••••••••" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-slate-600">Recuérdame</span>
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">
                    ¿Olvidaste tu contraseña?
                </Link>
            </div>

            <button type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-teal-500/20 transition hover:from-teal-700 hover:to-emerald-700 disabled:opacity-50"
                :disabled="form.processing">
                {{ form.processing ? 'Iniciando sesión...' : 'Iniciar sesión' }}
            </button>
        </form>
    </GuestLayout>
</template>
