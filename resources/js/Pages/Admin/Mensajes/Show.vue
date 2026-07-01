<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ mensaje: Object });

const form = useForm({
    estado: props.mensaje.estado === 'nuevo' ? 'leido' : props.mensaje.estado,
    respuesta: props.mensaje.respuesta ?? '',
});

const submit = () => form.put(`/admin/mensajes/${props.mensaje.id}`);

const fmtDate = (d) => d ? new Date(d).toLocaleString('es-MX') : '—';
</script>

<template>
    <Head :title="`Mensaje de ${mensaje.nombre}`" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/mensajes" class="text-slate-400 hover:text-slate-600">Mensajes</Link>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-800">{{ mensaje.nombre }}</span>
            </div>
        </template>

        <div class="mx-auto max-w-2xl space-y-6">
            <!-- Mensaje card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">{{ mensaje.asunto }}</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ mensaje.nombre }} &lt;{{ mensaje.email }}&gt;</p>
                        <p v-if="mensaje.telefono" class="text-sm text-slate-400">{{ mensaje.telefono }}</p>
                    </div>
                    <p class="text-xs text-slate-400">{{ fmtDate(mensaje.created_at) }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ mensaje.mensaje }}</p>
                </div>
            </div>

            <!-- Respuesta form -->
            <form @submit.prevent="submit" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-800">Gestionar mensaje</h3>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Estado</label>
                    <select v-model="form.estado" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none">
                        <option value="nuevo">Nuevo</option>
                        <option value="leido">Leído</option>
                        <option value="respondido">Respondido</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Notas internas / respuesta</label>
                    <textarea v-model="form.respuesta" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-teal-400 focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <Link href="/admin/mensajes" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">← Volver</Link>
                    <button type="submit" :disabled="form.processing"
                        class="rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:from-teal-700 disabled:opacity-50">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
