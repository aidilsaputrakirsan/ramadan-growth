<script setup lang="ts">
import { computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps<{
    perfectDays: number;
    todayProgress?: number; // percentage 0-100 (based on wajib only)
    wajibCompleted?: number; // out of 2
    sunnahCompleted?: number; // out of 6
}>();

// Check if today is perfect (wajib full)
const isTodayPerfect = computed(() => (props.wajibCompleted ?? 0) === 2);

// Main radial chart - Today's Wajib Progress
const todayChartOptions = computed(() => ({
    chart: {
        type: 'radialBar' as const,
        sparkline: { enabled: true },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 1000,
        }
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            hollow: {
                size: '65%',
                background: 'transparent',
            },
            track: {
                background: '#f3f4f6',
                strokeWidth: '100%',
                margin: 0,
                dropShadow: {
                    enabled: true,
                    top: 2,
                    left: 0,
                    blur: 4,
                    opacity: 0.1
                }
            },
            dataLabels: {
                name: {
                    show: true,
                    fontSize: '11px',
                    fontWeight: 600,
                    color: '#6b7280',
                    offsetY: -8
                },
                value: {
                    show: true,
                    fontSize: '24px',
                    fontWeight: 800,
                    color: '#1f2937',
                    offsetY: 4,
                    formatter: (val: number) => `${Math.round(val)}%`
                }
            }
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            type: 'horizontal',
            gradientToColors: ['#10b981'],
            stops: [0, 100]
        }
    },
    colors: ['#34d399'],
    labels: ['Ibadah Wajib'],
    stroke: {
        lineCap: 'round' as const
    }
}));

const todayChartSeries = computed(() => [props.todayProgress ?? 0]);

// Mini radial for Wajib - cleaner look
const wajibChartOptions = computed(() => ({
    chart: {
        type: 'radialBar' as const,
        sparkline: { enabled: true },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800,
        }
    },
    plotOptions: {
        radialBar: {
            hollow: {
                size: '55%',
            },
            track: {
                background: '#e5e7eb',
            },
            dataLabels: {
                name: { show: false },
                value: {
                    show: true,
                    fontSize: '12px',
                    fontWeight: 700,
                    color: '#1f2937',
                    offsetY: 4,
                    formatter: () => `${props.wajibCompleted ?? 0}/2`
                }
            }
        }
    },
    colors: ['#10b981'],
    stroke: { lineCap: 'round' as const }
}));

const wajibChartSeries = computed(() => {
    const completed = props.wajibCompleted ?? 0;
    return [Math.round((completed / 2) * 100)];
});

// Mini radial for Sunnah
const sunnahChartOptions = computed(() => ({
    chart: {
        type: 'radialBar' as const,
        sparkline: { enabled: true },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800,
        }
    },
    plotOptions: {
        radialBar: {
            hollow: {
                size: '55%',
            },
            track: {
                background: '#e5e7eb',
            },
            dataLabels: {
                name: { show: false },
                value: {
                    show: true,
                    fontSize: '12px',
                    fontWeight: 700,
                    color: '#1f2937',
                    offsetY: 4,
                    formatter: () => `${props.sunnahCompleted ?? 0}/6`
                }
            }
        }
    },
    colors: ['#f97316'],
    stroke: { lineCap: 'round' as const }
}));

const sunnahChartSeries = computed(() => {
    const completed = props.sunnahCompleted ?? 0;
    return [Math.round((completed / 6) * 100)];
});

// Progress to 30 days (based on perfect days = wajib complete)
const ramadanProgress = computed(() => Math.min(100, (props.perfectDays / 30) * 100));
</script>

<template>
    <div class="relative w-full rounded-2xl border border-gray-100 shadow-xl overflow-hidden bg-gradient-to-br from-slate-50 to-gray-50 p-6">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-emerald-100/40 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-orange-100/40 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Progress Hari Ini</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">
                        {{ isTodayPerfect ? 'Alhamdulillah, wajib terpenuhi!' : 'Yuk lengkapi ibadah wajibmu' }}
                    </p>
                </div>

                <div class="bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full animate-pulse" :class="isTodayPerfect ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                    <span class="text-sm font-bold text-gray-800">{{ perfectDays }}</span>
                    <span class="text-[10px] text-gray-400 font-semibold">HARI SEMPURNA</span>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Main Radial Chart -->
                <div class="col-span-2 bg-white rounded-2xl p-4 shadow-sm border border-gray-50">
                    <div class="flex items-center justify-center">
                        <div class="w-44 h-44">
                            <VueApexCharts
                                type="radialBar"
                                height="100%"
                                :options="todayChartOptions"
                                :series="todayChartSeries"
                            />
                        </div>
                    </div>
                    <p class="text-center text-[10px] text-gray-400 mt-1">
                        Wajib full = 1 hari sempurna
                    </p>
                </div>

                <!-- Side Stats -->
                <div class="flex flex-col gap-3">
                    <!-- Wajib Mini Chart -->
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-50 flex-1 flex flex-col">
                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide mb-1">Wajib</p>
                        <div class="flex-1 flex items-center justify-center">
                            <div class="w-14 h-14">
                                <VueApexCharts
                                    type="radialBar"
                                    height="100%"
                                    :options="wajibChartOptions"
                                    :series="wajibChartSeries"
                                />
                            </div>
                        </div>
                        <p class="text-[8px] text-gray-400 text-center">Pondasi</p>
                    </div>

                    <!-- Sunnah Mini Chart -->
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-50 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wide">Sunnah</p>
                            <span class="text-[8px] bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-full font-semibold">Lomba</span>
                        </div>
                        <div class="flex-1 flex items-center justify-center mt-1">
                            <div class="w-14 h-14">
                                <VueApexCharts
                                    type="radialBar"
                                    height="100%"
                                    :options="sunnahChartOptions"
                                    :series="sunnahChartSeries"
                                />
                            </div>
                        </div>
                        <p class="text-[8px] text-gray-400 text-center">Leaderboard</p>
                    </div>
                </div>
            </div>

            <!-- Ramadan Progress Bar -->
            <div class="mt-4 bg-white rounded-xl p-4 shadow-sm border border-gray-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-600">Target Ramadan</span>
                    <span class="text-xs font-bold text-emerald-600">{{ perfectDays }}/30 hari sempurna</span>
                </div>
                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div
                        class="h-full bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-600 rounded-full transition-all duration-1000 ease-out relative"
                        :style="{ width: `${ramadanProgress}%` }"
                    >
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-2 text-center">
                    Setiap ibadah wajib terpenuhi = 1 hari sempurna
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.animate-shimmer {
    animation: shimmer 2s infinite;
}
</style>
