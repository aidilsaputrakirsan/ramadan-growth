<script setup lang="ts">
import { computed } from 'vue'
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

const props = defineProps<{
    data: HeatmapDay[]
    hijriMonthName: string
    hijriYear: number
    selectedDate: string
}>()

const emit = defineEmits<{
    selectDate: [date: string]
}>()

// Get color based on completion status
const getColor = (day: HeatmapDay): string => {
    if (day.is_future) {
        return 'bg-gray-800/50 border-gray-700'
    }

    if (!day.has_log || day.completion_count === 0) {
        return 'bg-gray-800 border-gray-600 hover:border-gray-500'
    }

    if (day.is_perfect) {
        return 'bg-emerald-500 border-emerald-400 hover:border-emerald-300'
    }

    // Partial completion - gradient based on count (max 8 tasks)
    const intensity = Math.min(day.completion_count / 8, 1)
    if (intensity < 0.3) {
        return 'bg-emerald-900/60 border-emerald-800 hover:border-emerald-700'
    } else if (intensity < 0.6) {
        return 'bg-emerald-700/70 border-emerald-600 hover:border-emerald-500'
    } else {
        return 'bg-emerald-600/80 border-emerald-500 hover:border-emerald-400'
    }
}

const navigateToDate = (day: HeatmapDay) => {
    if (!day.is_future) {
        router.get(route('dashboard'), { date: day.gregorian_date }, {
            preserveState: true,
            preserveScroll: true,
        })
    }
}

// Split days into weeks (6 days per row for cleaner display)
const weeks = computed(() => {
    const result: HeatmapDay[][] = []
    for (let i = 0; i < props.data.length; i += 6) {
        result.push(props.data.slice(i, i + 6))
    }
    return result
})
</script>

<template>
    <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-4 sm:p-6 border border-gray-800">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">
                {{ hijriMonthName }} {{ hijriYear }} H
            </h3>
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded bg-gray-800 border border-gray-600"></span>
                    Kosong
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded bg-emerald-700/70 border border-emerald-600"></span>
                    Partial
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded bg-emerald-500 border border-emerald-400"></span>
                    Perfect
                </span>
            </div>
        </div>

        <!-- Heatmap Grid -->
        <div class="space-y-1.5">
            <div
                v-for="(week, weekIndex) in weeks"
                :key="weekIndex"
                class="flex gap-1.5"
            >
                <button
                    v-for="day in week"
                    :key="day.gregorian_date"
                    @click="navigateToDate(day)"
                    :disabled="day.is_future"
                    :class="[
                        'relative w-10 h-10 sm:w-12 sm:h-12 rounded-lg border-2 transition-all duration-200 flex items-center justify-center',
                        getColor(day),
                        day.is_future ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:scale-105',
                        day.gregorian_date === selectedDate ? 'ring-2 ring-white ring-offset-2 ring-offset-gray-900' : '',
                        day.is_today && day.gregorian_date !== selectedDate ? 'ring-2 ring-amber-400 ring-offset-1 ring-offset-gray-900' : ''
                    ]"
                    :title="`${day.hijri_formatted}\n${day.gregorian_formatted}${day.is_perfect ? '\n✓ Perfect Day!' : day.has_log ? `\n${day.completion_count}/8 ibadah` : ''}`"
                >
                    <span
                        :class="[
                            'text-sm font-medium',
                            day.is_future ? 'text-gray-600' : day.is_perfect ? 'text-white' : day.has_log ? 'text-emerald-200' : 'text-gray-400'
                        ]"
                    >
                        {{ day.hijri_day }}
                    </span>

                    <!-- Perfect day indicator -->
                    <span
                        v-if="day.is_perfect"
                        class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-400 rounded-full flex items-center justify-center"
                    >
                        <svg class="w-2 h-2 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </span>

                    <!-- Today indicator -->
                    <span
                        v-if="day.is_today"
                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-amber-400 rounded-full"
                    ></span>
                </button>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="mt-4 pt-4 border-t border-gray-800 flex justify-between text-sm">
            <div class="text-gray-400">
                <span class="text-emerald-400 font-semibold">{{ data.filter(d => d.is_perfect).length }}</span>
                Perfect Days
            </div>
            <div class="text-gray-400">
                <span class="text-emerald-400 font-semibold">{{ data.filter(d => d.has_log && !d.is_future).length }}</span>
                / {{ data.filter(d => !d.is_future).length }} Hari Terisi
            </div>
        </div>
    </div>
</template>
