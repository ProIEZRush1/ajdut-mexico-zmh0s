<script setup>
defineProps({
    markSize: { type: String, default: 'h-10 w-10' },
    textSize: { type: String, default: 'text-xl' },
    showText: { type: Boolean, default: true },
});
</script>

<template>
    <div class="flex items-center gap-2">
        <img
            src="/brand-logo.jpeg"
            alt="AJDUT Mexico"
            :class="[markSize, 'rounded-lg object-contain flex-shrink-0']"
        />
        <span
            v-if="showText"
            :class="[textSize, 'font-serif font-bold tracking-tight leading-tight']"
            style="color: #e9c574;"
        >
            AJDUT México
        </span>
    </div>
</template>
