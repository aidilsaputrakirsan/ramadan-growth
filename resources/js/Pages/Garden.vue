<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GrowingTree from '@/Components/GrowingTree.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    perfectDays: number;
    totalMonthDays: number;
    hijriMonthName: string;
    hijriYear: number;
}>();

const progress = computed(() => {
    if (props.totalMonthDays === 0) return 0;
    return Math.round((props.perfectDays / props.totalMonthDays) * 100);
});
</script>

<template>
    <Head title="Pohon Kebajikan" />

    <AuthenticatedLayout>
        <div class="px-4 py-8 space-y-8 max-w-2xl mx-auto">
            
            <!-- Header Section -->
            <div class="text-center space-y-2">
                <h1 class="text-3xl font-black text-white tracking-tight">Pohon Kebajikan</h1>
                <p class="text-emerald-400 font-bold uppercase tracking-widest text-xs">
                    {{ hijriMonthName }} {{ hijriYear }} H
                </p>
                <div class="w-12 h-1 bg-gradient-to-r from-emerald-500 to-cyan-500 mx-auto rounded-full mt-4"></div>
            </div>

            <!-- Main Tree Card -->
            <div class="glass-card p-8 relative overflow-hidden group transition-all duration-700 hover:shadow-2xl hover:shadow-emerald-500/10">
                <!-- Background Glow -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
                
                <div class="relative z-10 text-center space-y-6">
                    <p class="text-gray-400 text-sm font-medium">Bulan ini kamu telah mencapai</p>
                    <div class="flex items-center justify-center gap-3">
                        <span class="text-5xl font-black text-white tracking-tighter">{{ perfectDays }}</span>
                        <div class="text-left">
                            <div class="text-emerald-400 font-bold text-lg leading-none">Hari Sempurna</div>
                            <div class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mt-1">Dari {{ totalMonthDays }} Hari</div>
                        </div>
                    </div>

                    <!-- The Tree -->
                    <div class="py-4">
                        <GrowingTree 
                            :perfect-days="perfectDays"
                            :total-days="totalMonthDays"
                        />
                    </div>

                    <!-- Progress Stats -->
                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                            <div class="text-2xl font-black text-white">{{ progress }}%</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Tingkat Kesuburan</div>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                            <div class="text-2xl font-black text-white">{{ totalMonthDays - perfectDays }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Hari Tersisa</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Decorative GIF Area -->
            <div class="glass-card p-4 flex flex-col items-center justify-center gap-4 bg-gradient-to-b from-white/5 to-transparent">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Visualisasi Kebajikan</span>
                </div>
                <img 
                    src="/assets/lottie/Growing_Tree.gif" 
                    alt="Growing Tree Animation" 
                    class="w-full max-w-[120px] rounded-2xl shadow-2xl shadow-emerald-500/10 object-contain"
                />
            </div>

            <!-- Motivational Quote -->
            <div class="glass-card p-6 border-l-4 border-emerald-500 bg-gradient-to-r from-emerald-500/5 to-transparent">
                <p class="text-gray-300 italic leading-relaxed text-sm">
                    "Setiap kebaikan adalah benih. Di bulan yang penuh berkah ini, biarlah setiap Shalat, Puasa, dan Sedekahmu menjadi siraman yang menumbuhkan pohon pahala yang rimbun di akhirat kelak."
                </p>
            </div>

            <!-- Info Footer -->
            <div class="text-center pb-12">
                <p class="text-[10px] text-gray-600 font-bold uppercase tracking-[0.2em]">
                    Ramadan Growth • Digital Virtue Tree
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.glass-card {
    @apply bg-white/10 backdrop-blur-md rounded-3xl border border-white/10;
}
</style>
