<script setup lang="ts">
import { computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

interface TopUser {
    id: number;
    name: string;
    total_sunnah: number;
    perfect_days_count: number;
}

const props = defineProps<{
    users: TopUser[];
}>();

// Metallic colors for top positions - Gold, Silver, Bronze effect
const rankColors = [
    '#FFD700', // Gold - bright
    '#C0C0C0', // Silver - bright
    '#CD7F32', // Bronze - bright
    '#10b981', // Emerald
    '#06b6d4'  // Cyan
];
// Gradient end colors for metallic shine
const rankGradientColors = [
    '#FFA500', // Gold gradient to orange
    '#A8A8A8', // Silver gradient to darker
    '#8B4513', // Bronze gradient to saddle brown
    '#059669', // Emerald darker
    '#0891b2'  // Cyan darker
];
const rankEmojis = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
const rankLabels = ['🥇 #1', '🥈 #2', '🥉 #3', '4️⃣ #4', '5️⃣ #5'];

// Prepare data sorted by total_sunnah descending
const sortedUsers = computed(() => {
    return [...props.users].sort((a, b) => b.total_sunnah - a.total_sunnah);
});

// Max value for scaling
const maxSunnah = computed(() => {
    const max = Math.max(...sortedUsers.value.map(u => u.total_sunnah));
    return max > 0 ? max : 1;
});

// Chart options
const chartOptions = computed(() => ({
    chart: {
        type: 'bar' as const,
        toolbar: { show: false },
        animations: {
            enabled: true,
            easing: 'easeinout' as const,
            speed: 800,
            animateGradually: {
                enabled: true,
                delay: 150
            },
            dynamicAnimation: {
                enabled: true,
                speed: 350
            }
        },
        fontFamily: 'inherit'
    },
    plotOptions: {
        bar: {
            horizontal: true,
            borderRadius: 6,
            borderRadiusApplication: 'end' as const,
            barHeight: '65%',
            distributed: true,
        }
    },
    colors: rankColors,
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'light',
            type: 'horizontal',
            shadeIntensity: 0.4,
            gradientToColors: rankGradientColors,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 100],
        }
    },
    dataLabels: {
        enabled: true,
        textAnchor: 'end' as const,
        formatter: (val: number) => `${val}`,
        offsetX: -10,
        style: {
            fontSize: '13px',
            fontWeight: 800,
            colors: ['#fff']
        },
        dropShadow: {
            enabled: true,
            top: 1,
            left: 1,
            blur: 3,
            color: '#000',
            opacity: 0.5
        }
    },
    xaxis: {
        categories: rankLabels.slice(0, sortedUsers.value.length),
        labels: { show: false },
        axisBorder: { show: false },
        axisTicks: { show: false },
        max: Math.max(...sortedUsers.value.map(u => u.total_sunnah)) * 1.15 // Extra space
    },
    yaxis: {
        labels: {
            show: true,
            maxWidth: 60,
            style: {
                fontSize: '14px',
                fontWeight: 700,
                colors: ['#374151']
            },
            offsetX: 0
        }
    },
    grid: {
        show: false,
        padding: {
            left: 10,
            right: 10,
            top: -10,
            bottom: -5
        }
    },
    legend: { show: false },
    tooltip: {
        enabled: true,
        theme: 'light',
        custom: function({ seriesIndex, dataPointIndex }: { seriesIndex: number; dataPointIndex: number }) {
            const user = sortedUsers.value[dataPointIndex];
            return `<div class="px-3 py-2 bg-white shadow-lg rounded-lg border">
                <div class="font-bold text-gray-800">${user?.name || ''}</div>
                <div class="text-sm text-gray-600">${user?.total_sunnah || 0} ibadah sunnah</div>
            </div>`;
        }
    },
    states: {
        hover: {
            filter: {
                type: 'darken' as const,
                value: 0.9
            }
        }
    }
}));

const chartSeries = computed(() => [{
    name: 'Total Sunnah',
    data: sortedUsers.value.map(u => u.total_sunnah)
}]);
</script>

<template>
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <span>🏆</span> Top 5 Pejuang Sunnah
                    </h3>
                    <p class="text-orange-100 text-xs mt-1">Berlomba dalam kebaikan</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full">
                    <span class="text-white text-xs font-semibold">Ramadan 2026</span>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="px-2 sm:px-4 py-4">
            <div v-if="users.length > 0">
                <VueApexCharts
                    type="bar"
                    height="300"
                    :options="chartOptions"
                    :series="chartSeries"
                />
            </div>
            <div v-else class="flex flex-col items-center justify-center py-12 text-gray-400">
                <span class="text-4xl mb-2">📊</span>
                <p class="text-sm">Belum ada data leaderboard</p>
            </div>
        </div>

        <!-- Legend / Info -->
        <div class="px-4 sm:px-6 pb-4">
            <p class="text-center text-[10px] text-gray-400 mb-3">Total ibadah sunnah yang dilakukan</p>
            <div class="flex flex-wrap gap-2 justify-center">
                <div
                    v-for="(user, index) in sortedUsers.slice(0, 5)"
                    :key="user.id"
                    class="flex items-center gap-1.5 bg-gray-50 px-2.5 py-1.5 rounded-full text-xs"
                >
                    <span>{{ rankEmojis[index] }}</span>
                    <span class="font-medium text-gray-700 truncate max-w-[100px] sm:max-w-[120px]">{{ user.name }}</span>
                    <span class="font-bold" :style="{ color: rankColors[index] }">{{ user.total_sunnah }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
