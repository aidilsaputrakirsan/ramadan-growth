<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    count: number;
}>();

const fireIntensity = computed(() => {
    if (props.count === 0) return 'opacity-30 grayscale';
    if (props.count < 3) return 'opacity-80 scale-90';
    if (props.count < 7) return 'scale-100';
    return 'scale-110 drop-shadow-lg text-orange-500';
});
</script>

<template>
    <div class="group flex flex-col items-center justify-center p-3 bg-white border-2 border-orange-100 rounded-2xl shadow-sm hover:shadow-md hover:border-orange-200 transition-all duration-300">
        <span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-1 group-hover:text-orange-500 transition-colors">
            Streak Saat Ini
        </span>
        <div class="flex items-center gap-2">
            <span class="text-3xl font-black text-gray-800 tabular-nums leading-none tracking-tight">
                {{ count }}
            </span>
            <span 
                class="text-2xl transition-all duration-500 transform origin-bottom"
                :class="[fireIntensity, count > 0 ? 'animate-bounce' : '']"
                style="animation-duration: 2s;"
            >
                🔥
            </span>
        </div>
        <div v-if="count > 0" class="h-1 w-12 bg-gray-100 rounded-full mt-2 overflow-hidden">
             <div class="h-full bg-gradient-to-r from-yellow-400 to-red-500 w-full animate-pulse"></div>
        </div>
    </div>
</template>
