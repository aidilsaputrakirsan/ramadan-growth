<script setup lang="ts">
import { computed } from 'vue'

interface WajibStats {
    shalat_5_waktu: number
    puasa: number
}

const props = defineProps<{
    stats: WajibStats
    totalDays: number
}>()

const percentages = computed(() => {
    if (props.totalDays === 0) return { shalat: 0, puasa: 0 }
    return {
        shalat: Math.round((props.stats.shalat_5_waktu / props.totalDays) * 100),
        puasa: Math.round((props.stats.puasa / props.totalDays) * 100),
    }
})

// SVG Circle Config
const radius = 30
const circumference = 2 * Math.PI * radius
const strokeDashoffset = (percent: number) => {
    return circumference - (percent / 100) * circumference
}
</script>

<template>
    <div class="glass-card p-4 sm:p-6 w-full">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                Profil Wajib
            </h3>
            <div class="text-xs text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-full">
                {{ totalDays }} hari
            </div>
        </div>

        <!-- Rings Container -->
        <div class="flex items-center justify-center gap-6 sm:gap-10 py-2">
            
            <!-- Shalat Ring -->
            <div class="flex flex-col items-center">
                <div class="relative w-24 h-24">
                    <!-- Background Circle -->
                    <svg class="w-full h-full transform -rotate-90">
                        <circle
                            cx="48"
                            cy="48"
                            :r="radius"
                            stroke="currentColor"
                            stroke-width="6"
                            fill="transparent"
                            class="text-gray-800"
                        />
                        <!-- Progress Circle -->
                        <circle
                            cx="48"
                            cy="48"
                            :r="radius"
                            stroke="currentColor"
                            stroke-width="6"
                            fill="transparent"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="strokeDashoffset(percentages.shalat)"
                            stroke-linecap="round"
                            class="text-emerald-500 transition-all duration-1000 ease-out"
                        />
                    </svg>
                    <!-- Center Text -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ percentages.shalat }}%</span>
                    </div>
                </div>
                <div class="text-center mt-1">
                    <span class="block text-emerald-400 font-bold text-sm">{{ stats.shalat_5_waktu }} Full</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider">Shalat 5 Waktu</span>
                </div>
            </div>

            <!-- Puasa Ring -->
            <div class="flex flex-col items-center">
                <div class="relative w-24 h-24">
                    <!-- Background Circle -->
                    <svg class="w-full h-full transform -rotate-90">
                        <circle
                            cx="48"
                            cy="48"
                            :r="radius"
                            stroke="currentColor"
                            stroke-width="6"
                            fill="transparent"
                            class="text-gray-800"
                        />
                        <!-- Progress Circle -->
                        <circle
                            cx="48"
                            cy="48"
                            :r="radius"
                            stroke="currentColor"
                            stroke-width="6"
                            fill="transparent"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="strokeDashoffset(percentages.puasa)"
                            stroke-linecap="round"
                            class="text-teal-400 transition-all duration-1000 ease-out"
                        />
                    </svg>
                    <!-- Center Text -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ percentages.puasa }}%</span>
                    </div>
                </div>
                <div class="text-center mt-1">
                    <span class="block text-teal-400 font-bold text-sm">{{ stats.puasa }} Hari</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider">Puasa</span>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>
.glass-card {
    @apply bg-white/5 backdrop-blur-md rounded-2xl border border-white/10;
}
</style>
