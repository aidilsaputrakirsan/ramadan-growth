<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { PageProps } from '@/types';
import CheckInToggle from '@/Components/CheckInToggle.vue';
import MasjidEvolution from '@/Components/MasjidEvolution.vue';
import StreakCounter from '@/Components/StreakCounter.vue';
import { watch } from 'vue';

const page = usePage<PageProps>();

// Definitions
const ibadahGroups = {
    wajib: [
        { key: 'shalat_5_waktu', label: 'Shalat 5 Waktu', icon: '🕌', desc: 'Subuh, Dzuhur, Ashar, Maghrib, Isya' },
        { key: 'puasa', label: 'Puasa Ramadan', icon: '🥤', desc: 'Menahan diri dari fajar hingga maghrib' },
    ],
    sunnah: [
        { key: 'tarawih', label: 'Shalat Tarawih', icon: '🌙', desc: 'Qiyamul Lail berjamaah/sendiri' },
        { key: 'tilawah_quran', label: 'Tilawah Qur\'an', icon: '📖', desc: 'Minimal 1 halaman atau 1 juz' },
        { key: 'sedekah', label: 'Sedekah', icon: '🤲', desc: 'Berbagi rezeki atau kebaikan' },
        { key: 'tahajud', label: 'Shalat Tahajud', icon: '🌌', desc: 'Shalat malam setelah tidur' },
        { key: 'dhuha', label: 'Shalat Dhuha', icon: '☀️', desc: 'Shalat di waktu pagi' },
        { key: 'dzikir_pagi_petang', label: 'Dzikir Pagi/Petang', icon: '📿', desc: 'Al-Ma\'tsurat atau dzikir harian' },
    ]
};

// Initialize form dynamically
const allKeys = [...ibadahGroups.wajib, ...ibadahGroups.sunnah].map(i => i.key);
const initialTasks: Record<string, boolean> = {};
allKeys.forEach(key => {
    // @ts-ignore
    initialTasks[key] = page.props.todayLog?.tasks_completed?.[key] ?? false;
});

const form = useForm({
    date: new Date().toISOString().split('T')[0],
    tasks_completed: initialTasks
});

// Watch for changes and auto-submit
const autoSave = () => {
    form.post(route('daily-log.store'), {
        preserveScroll: true,
        preserveState: true,
    });
};

watch(() => form.tasks_completed, () => {
    autoSave();
}, { deep: true });
</script>

<template>
    <Head title="Ramadan Tracker" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-gray-800">
                    Ramadan Tracker 🌙
                </h2>
                <div class="text-sm font-medium text-gray-500 hidden sm:block">
                    {{ new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
                
                <!-- Top Section: Masjid & Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Masjid Evolution -->
                    <div class="md:col-span-2">
                        <MasjidEvolution 
                            :stage="page.props.masjidStage || 1" 
                            :perfect-days="page.props.totalPerfectDays || 0"
                        />
                    </div>

                    <!-- Stats Column -->
                    <div class="flex flex-col gap-4">
                        <StreakCounter :count="page.props.currentStreak || 0" />
                        
                        <div class="bg-white p-6 rounded-2xl border-2 border-emerald-50 shadow-sm flex-1 flex flex-col justify-center items-center text-center hover:border-emerald-100 transition-colors">
                            <div class="text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">
                                TOTAL PERFECT DAYS
                            </div>
                            <div class="text-5xl font-black text-gray-800 mb-2">
                                {{ page.props.totalPerfectDays || 0 }}
                            </div>
                            <div class="text-xs text-gray-400">
                                Keep building your masjid!
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Check-in Section -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-3">
                            <span class="bg-emerald-100 p-2 rounded-lg text-xl">📝</span> 
                            Check-in Ibadah Harian
                        </h3>
                        
                        <!-- Saving Indicator -->
                        <transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0"
                            enter-to-class="opacity-100"
                            leave-active-class="transition ease-in duration-200"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <span v-if="form.processing" class="text-sm text-gray-400 font-medium flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                             <span v-else-if="form.recentlySuccessful" class="text-sm text-emerald-500 font-medium flex items-center gap-1">
                                <span>✓</span> Saved
                            </span>
                        </transition>
                    </div>
                    
                    <!-- Wajib Section -->
                    <div class="mb-6">
                        <h4 class="text-sm uppercase tracking-wider font-bold text-emerald-600 mb-3 ml-1">
                            🔰 Wajib (Pondasi)
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <CheckInToggle
                                v-for="item in ibadahGroups.wajib"
                                :key="item.key"
                                v-model="form.tasks_completed[item.key]"
                                :label="item.label"
                                :icon="item.icon"
                                :description="item.desc"
                            />
                        </div>
                    </div>

                    <!-- Sunnah Section -->
                    <div>
                        <h4 class="text-sm uppercase tracking-wider font-bold text-orange-500 mb-3 ml-1">
                            🌟 Sunnah (Penyempurna)
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <CheckInToggle
                                v-for="item in ibadahGroups.sunnah"
                                :key="item.key"
                                v-model="form.tasks_completed[item.key]"
                                :label="item.label"
                                :icon="item.icon"
                                :description="item.desc"
                            />
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
