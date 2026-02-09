<script setup lang="ts">
import { computed } from 'vue'
import { Radar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend,
    type ChartOptions
} from 'chart.js'

ChartJS.register(
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
)

interface SunnahStats {
    tarawih: number
    tilawah_quran: number
    sedekah: number
    tahajud: number
    dhuha: number
    dzikir_pagi_petang: number
}

const props = defineProps<{
    stats: SunnahStats
    totalDays: number
}>()

// Labels untuk radar chart (nama ibadah sunnah)
const labels = [
    'Tarawih',
    'Tilawah',
    'Sedekah',
    'Tahajud',
    'Dhuha',
    'Dzikir'
]

// Hitung persentase untuk setiap ibadah sunnah
const percentages = computed(() => {
    if (props.totalDays === 0) return [0, 0, 0, 0, 0, 0]

    return [
        Math.round((props.stats.tarawih / props.totalDays) * 100),
        Math.round((props.stats.tilawah_quran / props.totalDays) * 100),
        Math.round((props.stats.sedekah / props.totalDays) * 100),
        Math.round((props.stats.tahajud / props.totalDays) * 100),
        Math.round((props.stats.dhuha / props.totalDays) * 100),
        Math.round((props.stats.dzikir_pagi_petang / props.totalDays) * 100),
    ]
})

// Tentukan kekuatan tertinggi user
const strongestSunnah = computed(() => {
    const maxIndex = percentages.value.indexOf(Math.max(...percentages.value))
    return labels[maxIndex]
})

const chartData = computed(() => ({
    labels: labels,
    datasets: [
        {
            label: 'Konsistensi (%)',
            data: percentages.value,
            backgroundColor: 'rgba(244, 114, 182, 0.25)',
            borderColor: 'rgba(244, 114, 182, 0.8)',
            borderWidth: 2,
            pointBackgroundColor: 'rgba(244, 114, 182, 1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 1,
            pointRadius: 4,
            pointHoverRadius: 6,
        }
    ]
}))

const chartOptions: ChartOptions<'radar'> = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: 'rgba(17, 24, 39, 0.9)',
            titleColor: '#fff',
            bodyColor: '#f472b6',
            borderColor: 'rgba(244, 114, 182, 0.3)',
            borderWidth: 1,
            padding: 10,
            callbacks: {
                label: function(context: any) {
                    return `${context.raw}% konsisten`
                }
            }
        }
    },
    scales: {
        r: {
            beginAtZero: true,
            max: 100,
            min: 0,
            ticks: {
                stepSize: 25,
                color: 'rgba(156, 163, 175, 0.6)',
                backdropColor: 'transparent',
                font: {
                    size: 9
                }
            },
            grid: {
                color: 'rgba(75, 85, 99, 0.3)'
            },
            angleLines: {
                color: 'rgba(75, 85, 99, 0.3)'
            },
            pointLabels: {
                color: 'rgba(209, 213, 219, 0.9)',
                font: {
                    size: 11,
                    weight: 500
                }
            }
        }
    }
}
</script>

<template>
    <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-4 sm:p-6 border border-gray-800">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">
                Profil Sunnah
            </h3>
            <div class="text-xs text-rose-400 bg-rose-400/10 px-2 py-1 rounded-full">
                {{ totalDays }} hari
            </div>
        </div>

        <!-- Radar Chart -->
        <div class="relative aspect-square max-w-[280px] mx-auto">
            <Radar :data="chartData" :options="chartOptions" />
        </div>

        <!-- Strongest Sunnah Badge -->
        <div class="mt-4 pt-4 border-t border-gray-800 text-center">
            <p class="text-xs text-gray-400 mb-1">Ibadah Sunnah Terkuat</p>
            <div class="inline-flex items-center gap-2 bg-rose-500/20 border border-rose-500/30 rounded-full px-4 py-2">
                <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-rose-300 font-semibold">{{ strongestSunnah }}</span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mt-4 grid grid-cols-3 gap-2 text-center">
            <div v-for="(percentage, index) in percentages" :key="index" class="bg-gray-800/50 rounded-lg p-2">
                <div class="text-rose-400 font-bold text-sm">{{ percentage }}%</div>
                <div class="text-gray-500 text-[10px]">{{ labels[index] }}</div>
            </div>
        </div>
    </div>
</template>
