<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <input
        class="rounded-lg border-white/20 bg-white/10 text-white placeholder-gray-400 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 backdrop-blur-sm"
        v-model="model"
        ref="input"
    />
</template>
