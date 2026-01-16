<script setup lang="ts">
import { computed } from 'vue';
import { Vue3Lottie } from 'vue3-lottie';

const props = defineProps<{
    stage: number;
    perfectDays: number;
}>();

const stageData = computed(() => {
    // Menggunakan Lottie Faisal Mosque yang sudah didownload
    const stages: Record<number, { label: string; color: string; bgGradient: string; lottie: string; description: string }> = {
        1: { 
            label: 'PONDASI', 
            color: 'text-orange-600', 
            bgGradient: 'from-orange-50 to-amber-50',
            lottie: '/lottie/Faisal-Mosque.json',
            description: 'Memulai dengan niat yang kuat...'
        },
        2: { 
            label: 'STRUKTUR', 
            color: 'text-emerald-600', 
            bgGradient: 'from-emerald-50 to-teal-50',
            lottie: '/lottie/Faisal-Mosque.json',
            description: 'Membangun keistiqomahan diri.'
        },
        3: { 
            label: 'KUBAH', 
            color: 'text-teal-700', 
            bgGradient: 'from-teal-50 to-cyan-50',
            lottie: '/lottie/Faisal-Mosque.json',
            description: 'Menuju puncak keberkahan.'
        },
        4: { 
            label: 'SEMPURNA', 
            color: 'text-emerald-800', 
            bgGradient: 'from-emerald-100 to-green-50',
            lottie: '/lottie/Faisal-Mosque.json',
            description: 'Masjid megah, hati yang tenang.'
        },
    };
    return stages[props.stage] || stages[4];
});

const progressPercent = computed(() => {
    return Math.min(100, (props.perfectDays / 30) * 100); 
});
</script>

<template>
    <div 
        class="relative w-full rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-700 ease-in-out"
        :class="`bg-gradient-to-br ${stageData.bgGradient}`"
    >
        <!-- Animated Background Effects -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Floating Circles -->
            <div class="absolute top-10 left-10 w-32 h-32 bg-white/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-emerald-200/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
            
            <!-- Sparkles -->
            <div class="absolute top-1/4 left-1/3 text-white/40 animate-ping text-xl">✨</div>
            <div class="absolute bottom-1/3 right-1/4 text-white/30 animate-ping text-lg" style="animation-delay: 2s">✨</div>
        </div>

        <!-- Main Image Container -->
        <div class="relative aspect-[16/10] flex items-center justify-center p-8">
            <!-- Aura effect -->
            <div class="absolute w-64 h-64 bg-emerald-400/10 rounded-full blur-[80px] animate-pulse"></div>
            
            <transition name="stage-change" mode="out-in">
                <div :key="stage" class="relative z-10 w-full max-w-sm flex items-center justify-center">
                    <Vue3Lottie
                        :animationLink="stageData.lottie"
                        :loop="true"
                        :autoPlay="true"
                        class="w-full h-auto drop-shadow-2xl"
                    />
                </div>
            </transition>
        </div>

        <!-- Description Overlay -->
        <div class="absolute inset-x-0 bottom-16 text-center pointer-events-none">
            <transition name="fade" mode="out-in">
                <p :key="stage" class="text-[10px] font-medium tracking-widest text-gray-400 uppercase">
                    {{ stageData.description }}
                </p>
            </transition>
        </div>

        <!-- Stage Label -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
            <div 
                class="bg-white/95 backdrop-blur-md px-8 py-3 rounded-full shadow-lg border border-white/50 group hover:scale-105 transition-transform cursor-default"
            >
                <span class="text-xs font-black tracking-[0.2em] uppercase" :class="stageData.color">
                    {{ stageData.label }}
                </span>
            </div>
        </div>

        <!-- Stats Badges -->
        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-4 py-2 rounded-full shadow-md border border-white/50 flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-sm font-black text-gray-800">{{ perfectDays }}</span>
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Hari Perfect</span>
        </div>

        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md w-12 h-12 rounded-2xl shadow-md border border-white/50 flex items-center justify-center transform rotate-3">
            <span class="text-xl font-black" :class="stageData.color">{{ stage }}</span>
        </div>

        <!-- Progress Bar -->
        <div class="absolute bottom-0 left-0 right-0 h-2 bg-gray-200/50">
            <div 
                class="h-full bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-600 transition-all duration-1000 ease-out relative overflow-hidden"
                :style="{ width: `${progressPercent}%` }"
            >
                <!-- Shimmer -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"></div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Floating Animation */
@keyframes float {
  0% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-15px) rotate(1deg); }
  100% { transform: translateY(0px) rotate(0deg); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Shimmer Animation */
@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

.animate-shimmer {
  animation: shimmer 3s infinite;
}

/* Transitions */
.stage-change-enter-active,
.stage-change-leave-active {
  transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.stage-change-enter-from {
  opacity: 0;
  transform: scale(0.8) translateY(20px);
}

.stage-change-leave-to {
  opacity: 0;
  transform: scale(1.1) translateY(-20px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
