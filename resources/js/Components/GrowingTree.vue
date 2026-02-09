<script setup lang="ts">
import { Vue3Lottie } from 'vue3-lottie';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    perfectDays: number;
    totalDays: number;
}>();

const lottieRef = ref();

// Calculate progress percentage (0 to 1)
const progress = computed(() => {
    if (props.totalDays === 0) return 0;
    return Math.min(props.perfectDays / props.totalDays, 1);
});

// Use a ref to track if lottie is ready
const isReady = ref(false);

const handleReady = () => {
    isReady.value = true;
    updateProgress();
};

const updateProgress = () => {
    if (lottieRef.value && isReady.value) {
        // Lottie goToAndStop uses frames or percentage. 
        // We'll use goToAndPlay then pause if needed, or goToAndStop.
        const totalFrames = lottieRef.value.getDuration(true);
        const targetFrame = totalFrames * progress.value;
        lottieRef.value.goToAndStop(targetFrame, true);
    }
};

watch(progress, () => {
    updateProgress();
});
</script>

<template>
    <div class="relative w-full aspect-square max-w-[280px] mx-auto flex items-center justify-center p-4">
        <!-- Decoration background -->
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/10 to-transparent rounded-full blur-3xl scale-125 opacity-50"></div>
        
        <!-- Animated Sparkles -->
        <div v-for="n in 8" :key="n" 
            class="absolute w-1 h-1 bg-white rounded-full sparkle animate-ping"
            :style="{ 
                top: `${30 + Math.random() * 40}%`, 
                left: `${15 + Math.random() * 70}%`,
                animationDelay: `${n * 0.4}s`,
                opacity: 0.8
            }"
        ></div>

        <!-- Floating Particles (Leaves) -->
        <div v-for="n in 5" :key="'p'+n" 
            class="absolute w-3 h-3 text-emerald-400/40 animate-leaf"
            :style="{ 
                top: `${40 + Math.random() * 30}%`, 
                left: `${10 + Math.random() * 80}%`,
                animationDelay: `${n * 0.8}s`
            }"
        >
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L7.38,17.84C9.58,18.81 11.77,19 14,19C20.75,19 22,12 22,12C22,12 22,11.5 17,8Z"/>
            </svg>
        </div>
        
        <Vue3Lottie
            ref="lottieRef"
            animation-link="/assets/lottie/growing_tree.json"
            :height="280"
            :width="280"
            :auto-play="false"
            :play-on-hover="false"
            @on-animation-loaded="handleReady"
            class="relative z-10"
        />

        <!-- Overlay Progress Text -->
        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 whitespace-nowrap">
            
        </div>
    </div>
</template>

<style scoped>
.glass-tag {
    background: rgba(16, 185, 129, 0.1);
    backdrop-filter: blur(8px);
}

@keyframes leaf-float {
    0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
    33% { transform: translate(10px, -15px) rotate(20deg) scale(1.1); }
    66% { transform: translate(-10px, -25px) rotate(-20deg) scale(0.9); }
}

.animate-leaf {
    animation: leaf-float 5s ease-in-out infinite;
}

.sparkle {
    box-shadow: 0 0 15px 3px rgba(255, 255, 255, 0.6);
}
</style>
