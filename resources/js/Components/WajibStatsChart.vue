<script setup lang="ts">
import { router } from '@inertiajs/vue3'

interface HeatmapDay {
    hijri_day: number
    gregorian_date: string
    gregorian_formatted: string
    hijri_formatted: string
    is_today: boolean
    is_past: boolean
    is_future: boolean
    has_log: boolean
    is_perfect: boolean
    tasks_completed: Record<string, boolean>
    completion_count: number
}

interface WajibStats {
    shalat_5_waktu: number
    puasa: number
}

const props = defineProps<{
    stats: WajibStats
    totalDays: number
    hijriMonth: string
    hijriYear: number
    heatmapData: HeatmapDay[]
    selectedDate: string
}>()

// Helper to determine the status color/class for a day
const getDayClass = (day: HeatmapDay) => {
    // Base classes
    let classes = ''
    
    // Selection state (High Priority)
    if (day.gregorian_date === props.selectedDate) {
        classes += ' ring-2 ring-white ring-offset-2 ring-offset-gray-900 z-10'
    }

    if (day.is_future) {
        return classes + ' bg-gray-800/50 border-gray-700 cursor-not-allowed opacity-50'
    }
    
    if (day.is_perfect) {
        return classes + ' bg-teal-500 border-teal-400 hover:border-teal-300 cursor-pointer hover:scale-105 shadow-[0_0_15px_rgba(20,184,166,0.3)]'
    }
    
    if (day.has_log) {
        // Logged but not perfect (Partial)
        // Check if at least one Wajib is done
        // Note: tasks_completed values are booleans
        const completed = day.tasks_completed || {};
        const wajibDone = !!completed['shalat_5_waktu'] || !!completed['puasa'];
        
        if (wajibDone) {
            return classes + ' bg-indigo-700 border-indigo-500 hover:border-indigo-400 cursor-pointer hover:scale-105'
        } else if (day.completion_count > 0) {
            // Did Sunnah but not Wajib -> Amber/Yellowish to differentiate
             return classes + ' bg-rose-900/50 border-rose-700/50 hover:border-rose-600 cursor-pointer hover:scale-105'
        } else {
             return classes + ' bg-gray-700/50 border-gray-600 hover:border-gray-500 cursor-pointer hover:scale-105'
        }
    }
    
    // Empty/No Log
    return classes + ' bg-gray-800 border-gray-700 hover:border-gray-600 cursor-pointer hover:scale-105'
}

const navigateToDate = (day: HeatmapDay) => {
    if (day.is_future) return
    
    router.get(route('dashboard'), { date: day.gregorian_date }, {
        preserveState: true,
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="glass-card p-4 sm:p-6 w-full">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                {{ hijriMonth }} {{ hijriYear }} H
            </h3>
            
            <!-- Legend (optional or minimal) -->
             <div class="flex items-center gap-2 text-[10px] text-gray-400">
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-sm bg-gray-800 border border-gray-700"></span>
                    Kosong
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-sm bg-indigo-700 border border-indigo-500"></span>
                    Isi
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-sm bg-teal-500 border border-teal-400"></span>
                    Perfect
                </span>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-6 sm:grid-cols-7 gap-2">
            <button
                v-for="day in heatmapData"
                :key="day.hijri_day"
                @click="navigateToDate(day)"
                :disabled="day.is_future"
                class="relative aspect-square rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center group"
                :class="getDayClass(day)"
                :title="`${day.hijri_formatted}\n${day.gregorian_formatted}${day.is_perfect ? '\n✓ Perfect Day!' : ''}`"
            >
                <!-- Day Number -->
                <span 
                    class="text-sm font-bold"
                    :class="day.is_perfect ? 'text-white' : day.is_future ? 'text-gray-600' : 'text-gray-400 group-hover:text-gray-200'"
                >
                    {{ day.hijri_day }}
                </span>

                <!-- Perfect Star Indicator -->
                <div v-if="day.is_perfect" class="absolute -top-1 -right-1">
                     <lord-icon
                        src="https://cdn.lordicon.com/surjmvno.json"
                        trigger="in"
                        colors="primary:#f472b6,secondary:#fb923c"
                        style="width:20px;height:20px">
                    </lord-icon>
                </div>

                <!-- Today Indicator -->
                <span
                    v-if="day.is_today"
                    class="absolute -bottom-1 w-8 h-1 bg-pink-400/80 rounded-full blur-[2px]"
                ></span>
            </button>
        </div>
        
        <!-- Summary Text -->
         <div class="mt-4 pt-4 border-t border-gray-700/50 flex justify-between text-xs text-gray-400">
             <div>
                <span class="text-cyan-400 font-bold">{{ stats.shalat_5_waktu }}</span> Shalat Full
             </div>
             <div>
                <span class="text-cyan-400 font-bold">{{ stats.puasa }}</span> Puasa
             </div>
             <div>
                <span class="text-pink-400 font-bold">{{ heatmapData.filter(d => d.is_perfect).length }}</span> Perfect Days
             </div>
         </div>
    </div>
</template>

<style scoped>
.glass-card {
    @apply bg-white/5 backdrop-blur-md rounded-2xl border border-white/10;
}
</style>
