<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { PageProps, LeaderboardUser } from '@/types';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import axios from 'axios';

interface Top5User {
    id: number;
    name: string;
    perfect_days_count: number;
    total_sunnah: number;
}

defineProps<{
    leaderboard: LeaderboardUser[];
    top5Sunnah: Top5User[];
}>();

const page = usePage<PageProps>();
const currentUserId = page.props.auth.user.id;

// Modal State
const showingUserModal = ref(false);
const selectedUserStats = ref<any>(null);
const isLoadingStats = ref(false);

const openUserStats = async (user: LeaderboardUser) => {
    showingUserModal.value = true;
    selectedUserStats.value = null;
    isLoadingStats.value = true;
    
    try {
        // @ts-ignore
        const response = await axios.get(route('community.show', user.id));
        selectedUserStats.value = response.data;
    } catch (e) {
        console.error(e);
        showingUserModal.value = false;
    } finally {
        isLoadingStats.value = false;
    }
};

const closeUserModal = () => {
    showingUserModal.value = false;
};

// Icon mapping untuk stats
const getStatIcon = (key: string): string => {
    const iconMap: Record<string, string> = {
        'tarawih': 'https://cdn.lordicon.com/lznlxwtc.json',
        'tilawah_quran': 'https://cdn.lordicon.com/wxnxiano.json',
        'sedekah': 'https://cdn.lordicon.com/uvqnvwbl.json',
        'tahajud': 'https://cdn.lordicon.com/kkvxgpti.json',
        'dhuha': 'https://cdn.lordicon.com/cllunfud.json',
        'dzikir_pagi_petang': 'https://cdn.lordicon.com/rqsvgwdj.json',
    };
    return iconMap[key] || 'https://cdn.lordicon.com/egiwmiit.json';
};

const getRankStyle = (rank: number) => {
    if (rank === 1) return 'from-yellow-500/30 to-amber-500/30 border-yellow-500/50';
    if (rank === 2) return 'from-gray-400/30 to-slate-400/30 border-gray-400/50';
    if (rank === 3) return 'from-orange-600/30 to-amber-600/30 border-orange-500/50';
    return 'from-white/5 to-white/5 border-white/10';
};

const getRankBadge = (rank: number) => {
    if (rank === 1) return '🥇';
    if (rank === 2) return '🥈';
    if (rank === 3) return '🥉';
    return rank;
};
</script>

<template>
    <Head title="Leaderboard" />

    <AuthenticatedLayout>
        <div class="px-4 py-6 space-y-4">
            
            <!-- Header Banner -->
            <div class="glass-card p-5 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-teal-500/20"></div>
                <div class="relative z-10">
                    <lord-icon
                        src="https://cdn.lordicon.com/oqdmuxru.json"
                        trigger="loop"
                        delay="1500"
                        colors="primary:#fbbf24,secondary:#f59e0b"
                        style="width:56px;height:56px"
                        class="mx-auto mb-2">
                    </lord-icon>
                    <h1 class="text-xl font-bold text-white mb-1">Klasemen Ramadan</h1>
                    <p class="text-emerald-300/80 text-xs italic">
                        "Berlomba-lombalah dalam kebaikan" - QS. Al-Baqarah: 148
                    </p>
                </div>
            </div>

            <!-- Top 3 Podium -->
            <div class="flex items-end justify-center gap-2 py-4" v-if="leaderboard.length >= 3">
                <!-- 2nd Place -->
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-gray-400 to-slate-500 flex items-center justify-center text-white font-bold text-lg shadow-lg mb-2">
                        {{ leaderboard[1]?.name?.charAt(0)?.toUpperCase() }}
                    </div>
                    <div class="glass-card px-3 py-2 text-center min-w-[80px]">
                        <div class="text-lg">🥈</div>
                        <div class="text-white text-xs font-medium truncate max-w-[70px]">{{ leaderboard[1]?.name }}</div>
                        <div class="text-emerald-400 text-xs font-bold">{{ leaderboard[1]?.perfect_days_count }} hari</div>
                    </div>
                </div>
                
                <!-- 1st Place -->
                <div class="flex flex-col items-center -mt-4">
                    <div class="w-18 h-18 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-yellow-500/30 mb-2 ring-4 ring-yellow-400/30" style="width: 72px; height: 72px;">
                        {{ leaderboard[0]?.name?.charAt(0)?.toUpperCase() }}
                    </div>
                    <div class="glass-card px-4 py-3 text-center min-w-[90px] border-yellow-500/30">
                        <div class="text-2xl">🥇</div>
                        <div class="text-white text-sm font-bold truncate max-w-[80px]">{{ leaderboard[0]?.name }}</div>
                        <div class="text-emerald-400 text-sm font-bold">{{ leaderboard[0]?.perfect_days_count }} hari</div>
                    </div>
                </div>
                
                <!-- 3rd Place -->
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white font-bold text-lg shadow-lg mb-2">
                        {{ leaderboard[2]?.name?.charAt(0)?.toUpperCase() }}
                    </div>
                    <div class="glass-card px-3 py-2 text-center min-w-[80px]">
                        <div class="text-lg">🥉</div>
                        <div class="text-white text-xs font-medium truncate max-w-[70px]">{{ leaderboard[2]?.name }}</div>
                        <div class="text-emerald-400 text-xs font-bold">{{ leaderboard[2]?.perfect_days_count }} hari</div>
                    </div>
                </div>
            </div>

            <!-- Full Leaderboard -->
            <div class="space-y-2">
                <button
                    v-for="(user, index) in leaderboard"
                    :key="user.id"
                    @click="openUserStats(user)"
                    class="w-full glass-card p-3 flex items-center gap-3 transition-all active:scale-[0.98] hover:bg-white/15"
                    :class="{ 
                        'ring-2 ring-emerald-500/50 bg-emerald-500/10': user.id === currentUserId,
                        [`bg-gradient-to-r ${getRankStyle(index + 1)}`]: index < 3
                    }"
                >
                    <!-- Rank -->
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center font-bold text-sm"
                         :class="index < 3 ? 'text-xl' : 'text-gray-400'">
                        {{ getRankBadge(index + 1) }}
                    </div>
                    
                    <!-- Avatar -->
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold">
                        {{ user.name?.charAt(0)?.toUpperCase() }}
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-left">
                        <div class="text-white font-medium text-sm flex items-center gap-2">
                            {{ user.name }}
                            <span v-if="user.id === currentUserId" class="text-[10px] bg-emerald-500/30 text-emerald-300 px-2 py-0.5 rounded-full">Kamu</span>
                        </div>
                        <div class="text-gray-400 text-xs">{{ user.perfect_days_count }} hari sempurna</div>
                    </div>
                    
                    <!-- Arrow -->
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Empty State -->
            <div v-if="leaderboard.length === 0" class="glass-card p-8 text-center">
                <lord-icon
                    src="https://cdn.lordicon.com/msoeawqm.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#9ca3af"
                    style="width:64px;height:64px"
                    class="mx-auto mb-3">
                </lord-icon>
                <p class="text-gray-400">Belum ada data.</p>
                <p class="text-gray-500 text-sm mt-1">Jadilah yang pertama!</p>
            </div>

        </div>

        <!-- Detail Modal -->
        <Modal :show="showingUserModal" @close="closeUserModal">
            <div class="bg-slate-900 p-6 rounded-2xl border border-white/10">
                <!-- Loading State -->
                <div v-if="isLoadingStats" class="flex flex-col items-center justify-center py-12">
                    <lord-icon
                        src="https://cdn.lordicon.com/xjovhxra.json"
                        trigger="loop"
                        colors="primary:#34d399,secondary:#10b981"
                        style="width:64px;height:64px">
                    </lord-icon>
                    <p class="text-gray-400 text-sm mt-3">Memuat data...</p>
                </div>
                
                <!-- Content -->
                <div v-else-if="selectedUserStats">
                    <!-- User Header -->
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/30">
                                <span class="text-3xl text-white font-bold">{{ selectedUserStats.user.name?.charAt(0)?.toUpperCase() }}</span>
                            </div>
                            <div class="absolute -bottom-1 -right-1">
                                <lord-icon
                                    src="https://cdn.lordicon.com/egiwmiit.json"
                                    trigger="loop"
                                    delay="2000"
                                    colors="primary:#fbbf24"
                                    style="width:28px;height:28px">
                                </lord-icon>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-white mt-3">{{ selectedUserStats.user.name }}</h2>
                        <div class="flex items-center justify-center gap-2 mt-1">
                            <lord-icon
                                src="https://cdn.lordicon.com/surjmvno.json"
                                trigger="loop"
                                delay="3000"
                                colors="primary:#fbbf24"
                                style="width:20px;height:20px">
                            </lord-icon>
                            <span class="text-emerald-400 font-bold">{{ selectedUserStats.user.perfect_days }} Hari Sempurna</span>
                        </div>
                    </div>

                    <!-- Stats Section -->
                    <div class="bg-white/5 rounded-xl p-4 mb-4">
                        <h3 class="text-xs uppercase font-bold text-gray-500 tracking-wider mb-3 flex items-center gap-2">
                            <lord-icon
                                src="https://cdn.lordicon.com/gqdnbnwt.json"
                                trigger="hover"
                                colors="primary:#9ca3af"
                                style="width:16px;height:16px">
                            </lord-icon>
                            Statistik Ibadah Sunnah
                        </h3>
                        
                        <div class="space-y-2">
                            <div v-for="(stat, index) in selectedUserStats.stats" :key="stat.key" 
                                 class="flex items-center justify-between p-3 bg-white/5 rounded-xl hover:bg-white/10 transition-all">
                                <div class="flex items-center gap-3">
                                    <lord-icon
                                        :src="getStatIcon(stat.key)"
                                        trigger="hover"
                                        colors="primary:#34d399,secondary:#10b981"
                                        style="width:28px;height:28px">
                                    </lord-icon>
                                    <div class="font-medium text-gray-300 text-sm">{{ stat.label }}</div>
                                </div>
                                <div class="font-bold text-emerald-400 bg-emerald-500/20 px-3 py-1 rounded-full text-xs">
                                    {{ stat.count }}x
                                </div>
                            </div>
                            
                            <!-- Empty State -->
                            <div v-if="selectedUserStats.stats.length === 0" class="text-center py-6">
                                <lord-icon
                                    src="https://cdn.lordicon.com/msoeawqm.json"
                                    trigger="loop"
                                    delay="1000"
                                    colors="primary:#6b7280"
                                    style="width:48px;height:48px"
                                    class="mx-auto mb-2">
                                </lord-icon>
                                <p class="text-gray-500 text-sm">Belum ada data ibadah sunnah.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Close Button -->
                <button @click="closeUserModal" class="w-full py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 font-medium text-sm transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <lord-icon
                        src="https://cdn.lordicon.com/rivoakkk.json"
                        trigger="hover"
                        colors="primary:#ffffff"
                        style="width:18px;height:18px">
                    </lord-icon>
                    Tutup
                </button>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.glass-card {
    @apply bg-white/10 backdrop-blur-md rounded-2xl border border-white/10;
}
</style>
