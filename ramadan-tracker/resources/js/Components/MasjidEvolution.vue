<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    stage: number;
    perfectDays: number;
}>();

const progressPercent = computed(() => {
    // Calculate progress to next stage or completion
    // Max 30 days for full ramadan context, but strict stages:
    // Stage 1 (1): 0-6 days
    // Stage 2 (2): 7-14 days
    // Stage 3 (3): 15-21 days
    // Stage 4 (4): 22+ days
    // This display bar is just visual flair
    return Math.min(100, (props.perfectDays / 30) * 100); 
});
</script>

<template>
    <div class="relative w-full aspect-video bg-gradient-to-b from-sky-200 to-blue-50 rounded-2xl border-4 border-white shadow-xl overflow-hidden group">
        <!-- Sun/Moon Effect -->
        <div class="absolute top-4 right-4 w-12 h-12 rounded-full bg-yellow-300 shadow-lg animate-pulse opacity-80"></div>
        
        <!-- Cloud Decorations -->
        <div class="absolute top-8 left-10 text-4xl opacity-80 animate-bounce" style="animation-duration: 3s">☁️</div>
        <div class="absolute top-16 right-20 text-3xl opacity-60 animate-bounce" style="animation-duration: 4s">☁️</div>

        <!-- Main Stage Content -->
        <div class="absolute inset-x-0 bottom-0 h-3/4 flex flex-col items-center justify-end pb-8 transition-all duration-500">
            <transition name="fade" mode="out-in">
                <div :key="stage" class="text-center">
                    <!-- Placeholder Illustrations until Assets arrive -->
                    <div v-if="stage === 1" class="transform scale-100 transition-transform hover:scale-110">
                        <div class="text-[80px]">🏗️</div>
                        <div class="mt-2 bg-white/80 backdrop-blur-sm px-4 py-1 rounded-full text-xs font-bold text-gray-600">
                            PONDASI
                        </div>
                    </div>
                    
                    <div v-else-if="stage === 2" class="transform scale-100 transition-transform hover:scale-110">
                        <div class="text-[80px]">🧱</div>
                        <div class="mt-2 bg-white/80 backdrop-blur-sm px-4 py-1 rounded-full text-xs font-bold text-emerald-600">
                            STRUKTUR
                        </div>
                    </div>
                    
                    <div v-else-if="stage === 3" class="transform scale-100 transition-transform hover:scale-110">
                        <div class="text-[80px]">🕌</div>
                         <div class="mt-2 bg-white/80 backdrop-blur-sm px-4 py-1 rounded-full text-xs font-bold text-emerald-700">
                            KUBAH
                        </div>
                    </div>
                    
                    <div v-else class="transform scale-100 transition-transform hover:scale-110">
                        <div class="text-[90px] drop-shadow-2xl">✨🕌✨</div>
                        <div class="mt-2 bg-white/90 backdrop-blur-md px-6 py-1 rounded-full text-sm font-extrabold text-emerald-800 tracking-wider shadow-sm">
                            SEMPURNA
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <!-- Visual Progress Bar -->
        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-black/10">
            <div 
                class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.7)] transition-all duration-1000 ease-out"
                :style="{ width: `${progressPercent}%` }"
            ></div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
