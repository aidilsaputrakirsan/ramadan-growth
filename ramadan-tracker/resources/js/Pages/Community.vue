<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { PageProps, LeaderboardUser } from '@/types';
import LeaderboardRow from '@/Components/LeaderboardRow.vue';
import LeaderboardChart from '@/Components/LeaderboardChart.vue';
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
</script>

<template>
    <Head title="Community Leaderboard" />

    <AuthenticatedLayout>
        <template #header>
             <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-gray-800 flex items-center gap-2">
                    <span>🏆</span> Klasemen
                </h2>
                 
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-center mb-8 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>

                    <h3 class="text-xl font-bold mb-2 relative z-10">
                        Fastabiqul Khairat 🚀
                    </h3>
                    <p class="text-emerald-50 text-sm relative z-10 italic">
                        "Maka berlomba-lombalah kamu dalam berbuat kebaikan." (QS. Al-Baqarah: 148)
                    </p>
                </div>

                <!-- Top 5 Sunnah Chart -->
                <div class="mb-8">
                    <LeaderboardChart :users="top5Sunnah" />
                </div>

                <div class="space-y-1">
                    <LeaderboardRow 
                        v-for="(user, index) in leaderboard" 
                        :key="user.id"
                        :rank="index + 1"
                        :user="user"
                        :is-current-user="user.id === currentUserId"
                        @click="openUserStats(user)"
                        class="cursor-pointer hover:bg-gray-50 transition-colors"
                    />
                </div>

                <div v-if="leaderboard.length === 0" class="text-center py-12 text-gray-400 bg-white rounded-xl border border-dashed border-gray-300">
                    <div class="text-4xl mb-2">🕋</div>
                    <p>Belum ada data.</p>
                    <p class="text-sm mt-1">Jadilah yang pertama memulai kebaikan!</p>
                </div>

                <div class="mt-8 text-center text-xs text-gray-400">
                    Klik nama user untuk melihat detail statistik ibadah mereka.
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <Modal :show="showingUserModal" @close="closeUserModal">
            <div class="p-6 bg-white overflow-hidden shadow-xl transform transition-all sm:rounded-lg">
                <div v-if="isLoadingStats" class="flex justify-center items-center py-12">
                    <svg class="animate-spin h-8 w-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                <div v-else-if="selectedUserStats">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <span class="text-2xl text-white font-bold">{{ selectedUserStats.user.name?.charAt(0)?.toUpperCase() }}</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ selectedUserStats.user.name }}
                        </h2>
                        <div class="text-emerald-600 font-medium">
                            {{ selectedUserStats.user.perfect_days }} Hari Sempurna
                        </div>
                    </div>

                    <h3 class="text-sm uppercase font-bold text-gray-400 tracking-wider mb-4 border-b pb-2">
                        Statistik Ibadah Sunnah
                    </h3>
                    
                    <div class="space-y-3">
                        <div v-for="stat in selectedUserStats.stats" :key="stat.key" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                             <div class="flex items-center gap-3">
                                 <div class="text-2xl">{{ stat.icon }}</div>
                                 <div class="font-medium text-gray-700">{{ stat.label }}</div>
                             </div>
                             <div class="font-bold text-emerald-600 bg-emerald-100 px-3 py-1 rounded-full text-sm">
                                 {{ stat.count }}x
                             </div>
                        </div>
                        <div v-if="selectedUserStats.stats.length === 0" class="text-center text-gray-400 py-4 italic">
                            Belum ada data detail.
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button @click="closeUserModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
