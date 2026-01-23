<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SunnahRadarChart from '@/Components/SunnahRadarChart.vue';
import WajibStatsChart from '@/Components/WajibStatsChart.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { PageProps } from '@/types';
import { watch, computed, ref, onUnmounted } from 'vue';

interface DateInfo {
    gregorian: {
        day: number
        month: number
        month_name: string
        year: number
        day_name: string
        formatted: string
    }
    hijri: {
        day: number
        month: number
        month_name: string
        year: number
        formatted: string
    }
    combined: string
}

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

interface SunnahStats {
    tarawih: number
    tilawah_quran: number
    sedekah: number
    tahajud: number
    dhuha: number
    dzikir_pagi_petang: number
}

interface WajibStats {
    shalat_5_waktu: number
    puasa: number
}

const page = usePage<PageProps & {
    dateInfo: DateInfo
    heatmapData: HeatmapDay[]
    hijriMonth: number
    hijriMonthName: string
    hijriYear: number
    sunnahStats: SunnahStats
    wajibStats: WajibStats
    totalMonthDays: number
}>();

// Definitions
const ibadahGroups = {
    wajib: [
        { key: 'shalat_5_waktu', label: 'Shalat 5 Waktu', icon: 'https://cdn.lordicon.com/jjoolpwc.json', desc: 'Subuh, Dzuhur, Ashar, Maghrib, Isya' },
        { key: 'puasa', label: 'Puasa Ramadan', icon: 'https://cdn.lordicon.com/mqdkoaef.json', desc: 'Menahan diri dari fajar hingga maghrib' },
    ],
    sunnah: [
        { key: 'tarawih', label: 'Tarawih', icon: 'https://cdn.lordicon.com/lznlxwtc.json', desc: 'Qiyamul Lail' },
        { key: 'tilawah_quran', label: 'Tilawah', icon: 'https://cdn.lordicon.com/wxnxiano.json', desc: 'Baca Al-Quran' },
        { key: 'sedekah', label: 'Sedekah', icon: 'https://cdn.lordicon.com/uvqnvwbl.json', desc: 'Berbagi' },
        { key: 'tahajud', label: 'Tahajud', icon: 'https://cdn.lordicon.com/kkvxgpti.json', desc: 'Shalat malam' },
        { key: 'dhuha', label: 'Dhuha', icon: 'https://cdn.lordicon.com/cllunfud.json', desc: 'Shalat pagi' },
        { key: 'dzikir_pagi_petang', label: 'Dzikir', icon: 'https://cdn.lordicon.com/rqsvgwdj.json', desc: 'Pagi & Petang' },
    ]
};

// Current selected date - sync with backend
const selectedDate = ref((page.props.selectedDate as string) || new Date().toISOString().split('T')[0]);

// Ensure selectedDate updates when props change (navigation via chart)
watch(() => page.props.selectedDate, (newDate) => {
    if (newDate) {
        selectedDate.value = newDate as string;
    }
});

// Save status for better UI feedback
const saveStatus = ref<'idle' | 'saving' | 'saved'>('idle');
let savedTimeout: ReturnType<typeof setTimeout> | null = null;

// Initialize form with proper typing
type IbadahKey = string;
const allKeys: IbadahKey[] = [...ibadahGroups.wajib, ...ibadahGroups.sunnah].map(i => i.key);
const initialTasks: Record<IbadahKey, boolean> = {};
const todayLogTasks = page.props.todayLog?.tasks_completed as Record<string, boolean> | undefined;
allKeys.forEach(key => {
    initialTasks[key] = todayLogTasks?.[key] ?? false;
});

const form = useForm({
    date: selectedDate.value,
    tasks_completed: initialTasks
});

// Date navigation functions
const changeDate = (days: number) => {
    const current = new Date(selectedDate.value);
    current.setDate(current.getDate() + days);
    const newDate = current.toISOString().split('T')[0];
    
    // Prevent future dates
    const today = new Date().toISOString().split('T')[0];
    if (newDate > today) return;
    
    selectedDate.value = newDate;
    router.get(route('dashboard'), { date: newDate }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const goToToday = () => {
    selectedDate.value = new Date().toISOString().split('T')[0];
    router.get(route('dashboard'), {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Update form date when selected date changes
watch(selectedDate, (newDate) => {
    form.date = newDate;
});

// Sync form with server data after reload (but don't trigger save)
watch(() => page.props.todayLog, (newLog) => {
    // Only sync if not currently saving
    if (saveStatus.value === 'saving') return;

    const newLogTasks = newLog?.tasks_completed as Record<string, boolean> | undefined;
    allKeys.forEach(key => {
        form.tasks_completed[key] = newLogTasks?.[key] ?? false;
    });
}, { deep: true });

// Auto-save with debounce to prevent rapid fire
let saveTimeout: ReturnType<typeof setTimeout> | null = null;
let isUserAction = false;

const autoSave = () => {
    // Only save if triggered by user action
    if (!isUserAction) return;
    isUserAction = false;

    if (saveTimeout) clearTimeout(saveTimeout);
    if (savedTimeout) clearTimeout(savedTimeout);

    saveStatus.value = 'saving';

    saveTimeout = setTimeout(() => {
        form.post(route('daily-log.store'), {
            preserveScroll: true,
            preserveState: true, // Keep state to prevent UI glitches
            onSuccess: () => {
                saveStatus.value = 'saved';
                savedTimeout = setTimeout(() => {
                    saveStatus.value = 'idle';
                }, 2000);
            },
            onError: () => {
                saveStatus.value = 'idle';
            },
        });
    }, 300);
};

watch(() => form.tasks_completed, () => {
    autoSave();
}, { deep: true });

// Computed
const wajibKeys = ibadahGroups.wajib.map(i => i.key);
const sunnahKeys = ibadahGroups.sunnah.map(i => i.key);

const wajibCompleted = computed(() => wajibKeys.filter(key => form.tasks_completed[key]).length);
const sunnahCompleted = computed(() => sunnahKeys.filter(key => form.tasks_completed[key]).length);
const todayProgress = computed(() => Math.round((wajibCompleted.value / wajibKeys.length) * 100));

const isToday = computed(() => {
    return selectedDate.value === new Date().toISOString().split('T')[0];
});

const isFutureDate = computed(() => {
    return selectedDate.value > new Date().toISOString().split('T')[0];
});

const formattedDate = computed(() => {
    // Use dateInfo from backend if available
    if (page.props.dateInfo) {
        return page.props.dateInfo.gregorian.formatted;
    }
    const date = new Date(selectedDate.value + 'T00:00:00');
    return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
});

const hijriDate = computed(() => {
    return page.props.dateInfo?.hijri?.formatted ?? '';
});

// Computed for heatmap to ensure reactivity
const heatmapData = computed(() => page.props.heatmapData || []);

const toggleTask = (key: string) => {
    isUserAction = true;
    form.tasks_completed[key] = !form.tasks_completed[key];
};

// Cleanup timeouts when component unmounts to prevent memory leaks
onUnmounted(() => {
    if (saveTimeout) clearTimeout(saveTimeout);
    if (savedTimeout) clearTimeout(savedTimeout);
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="px-4 py-6 space-y-4">
            
            <!-- Date Navigation Card -->
            <div class="glass-card p-4">
                <div class="flex items-center justify-between mb-3">
                    <button 
                        @click="changeDate(-1)"
                        class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-gray-300 hover:text-white transition-all active:scale-95"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <div class="text-center flex-1">
                        <p class="text-emerald-400 text-xs font-medium mb-0.5">
                            {{ isToday ? 'Hari Ini' : 'Riwayat' }}
                        </p>
                        <p class="text-white text-sm font-bold">{{ formattedDate }}</p>
                        <p v-if="hijriDate" class="text-amber-400/80 text-xs mt-0.5">{{ hijriDate }}</p>
                    </div>
                    
                    <button 
                        @click="changeDate(1)"
                        :disabled="isFutureDate"
                        class="p-2 rounded-lg transition-all active:scale-95"
                        :class="isFutureDate 
                            ? 'bg-white/5 text-gray-600 cursor-not-allowed' 
                            : 'bg-white/10 hover:bg-white/20 text-gray-300 hover:text-white'"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                
                <button 
                    v-if="!isToday"
                    @click="goToToday"
                    class="w-full py-2 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 rounded-lg text-emerald-400 text-xs font-medium transition-all active:scale-[0.98]"
                >
                    Kembali ke Hari Ini
                </button>
            </div>

            <!-- Greeting Card -->
            <div class="glass-card p-5 flex items-center gap-4">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <img 
                        v-if="$page.props.auth.user.avatar" 
                        :src="$page.props.auth.user.avatar.startsWith('http') ? $page.props.auth.user.avatar : '/storage/' + $page.props.auth.user.avatar" 
                        alt="Avatar" 
                        class="w-12 h-12 rounded-full border-2 border-emerald-500/50 object-cover bg-slate-800"
                    >
                    <div 
                        v-else 
                        class="w-12 h-12 rounded-full border-2 border-emerald-500/50 bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold text-lg"
                    >
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                </div>
                
                <!-- Text -->
                <div>
                    <p class="text-emerald-400 text-sm font-medium mb-0.5">Assalamu'alaikum</p>
                    <h1 class="text-white text-xl font-bold leading-tight">{{ $page.props.auth.user.name }}</h1>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 gap-3">
                <!-- Streak -->
                <div class="glass-card p-4 text-center group hover:scale-[1.02] transition-transform">
                    <lord-icon
                        src="https://cdn.lordicon.com/lsrcesku.json"
                        trigger="loop"
                        delay="2000"
                        colors="primary:#f59e0b,secondary:#fbbf24"
                        style="width:48px;height:48px"
                        class="mx-auto">
                    </lord-icon>
                    <div class="text-3xl font-black text-white mt-1">{{ page.props.currentStreak || 0 }}</div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Hari Streak</div>
                </div>
                
                <!-- Perfect Days -->
                <div class="glass-card p-4 text-center group hover:scale-[1.02] transition-transform">
                    <lord-icon
                        src="https://cdn.lordicon.com/surjmvno.json"
                        trigger="loop"
                        delay="2500"
                        colors="primary:#fbbf24,secondary:#f59e0b"
                        style="width:48px;height:48px"
                        class="mx-auto">
                    </lord-icon>
                    <div class="text-3xl font-black text-white mt-1">{{ page.props.totalPerfectDays || 0 }}</div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Hari Sempurna</div>
                </div>
            </div>

            <!-- Wajib Stats Chart -->
            <WajibStatsChart
                v-if="page.props.wajibStats && page.props.totalMonthDays > 0"
                :stats="page.props.wajibStats"
                :total-days="page.props.totalMonthDays"
                :hijri-month="page.props.hijriMonthName"
                :hijri-year="page.props.hijriYear"
                :heatmap-data="page.props.heatmapData"
                :selected-date="selectedDate"
            />

            <!-- Sunnah Radar Chart -->
            <SunnahRadarChart
                v-if="page.props.sunnahStats && page.props.totalMonthDays > 0"
                :stats="page.props.sunnahStats"
                :total-days="page.props.totalMonthDays"
            />

            <!-- Today Progress -->
            <div class="glass-card p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-300">Progress Hari Ini</span>
                    <span class="text-emerald-400 font-bold">{{ todayProgress }}%</span>
                </div>
                <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                    <div 
                        class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500"
                        :style="{ width: todayProgress + '%' }"
                    ></div>
                </div>
                <div class="flex justify-between mt-2 text-xs text-gray-500">
                    <span>Wajib: {{ wajibCompleted }}/{{ wajibKeys.length }}</span>
                    <span>Sunnah: {{ sunnahCompleted }}/{{ sunnahKeys.length }}</span>
                </div>
            </div>

            <!-- Saving Indicator -->
            <div class="h-6 flex items-center justify-center">
                <transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                    mode="out-in"
                >
                    <span v-if="saveStatus === 'saving'" key="saving" class="text-xs text-gray-400 flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                    <span v-else-if="saveStatus === 'saved'" key="saved" class="text-xs text-emerald-400 flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tersimpan
                    </span>
                </transition>
            </div>

            <!-- Wajib Section -->
            <div>
                <h3 class="text-xs uppercase tracking-wider font-bold text-emerald-400 mb-3 px-1 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    Ibadah Wajib
                </h3>
                <div class="space-y-2">
                    <button
                        v-for="item in ibadahGroups.wajib"
                        :key="item.key"
                        @click="toggleTask(item.key)"
                        class="checkin-card w-full"
                        :class="{ 'checkin-card-active': form.tasks_completed[item.key] }"
                    >
                        <div class="flex items-center gap-3">
                            <lord-icon
                                :src="item.icon"
                                :trigger="form.tasks_completed[item.key] ? 'loop' : 'hover'"
                                :delay="form.tasks_completed[item.key] ? '2000' : '0'"
                                :colors="form.tasks_completed[item.key] ? 'primary:#34d399,secondary:#10b981' : 'primary:#9ca3af,secondary:#6b7280'"
                                style="width:40px;height:40px">
                            </lord-icon>
                            <div class="text-left flex-1">
                                <div class="font-semibold text-white text-sm">{{ item.label }}</div>
                                <div class="text-[11px] text-gray-400">{{ item.desc }}</div>
                            </div>
                            <div 
                                class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                                :class="form.tasks_completed[item.key] 
                                    ? 'bg-emerald-500 border-emerald-500 text-white' 
                                    : 'border-gray-600'"
                            >
                                <svg v-if="form.tasks_completed[item.key]" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Sunnah Section -->
            <div>
                <h3 class="text-xs uppercase tracking-wider font-bold text-amber-400 mb-3 px-1 flex items-center gap-2">
                    <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                    Ibadah Sunnah
                </h3>
                <div class="grid grid-cols-3 gap-2">
                    <button
                        v-for="item in ibadahGroups.sunnah"
                        :key="item.key"
                        @click="toggleTask(item.key)"
                        class="checkin-card-mini"
                        :class="{ 'checkin-card-mini-active': form.tasks_completed[item.key] }"
                    >
                        <lord-icon
                            :src="item.icon"
                            :trigger="form.tasks_completed[item.key] ? 'loop' : 'hover'"
                            :delay="form.tasks_completed[item.key] ? '2000' : '0'"
                            :colors="form.tasks_completed[item.key] ? 'primary:#fbbf24,secondary:#f59e0b' : 'primary:#9ca3af,secondary:#6b7280'"
                            style="width:32px;height:32px">
                        </lord-icon>
                        <span class="text-[10px] font-medium text-white leading-tight mt-1">{{ item.label }}</span>
                        <div 
                            class="absolute top-1.5 right-1.5 w-3 h-3 rounded-full border flex items-center justify-center transition-all"
                            :class="form.tasks_completed[item.key] 
                                ? 'bg-amber-500 border-amber-500' 
                                : 'border-gray-600'"
                        >
                            <svg v-if="form.tasks_completed[item.key]" class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.glass-card {
    @apply bg-white/10 backdrop-blur-md rounded-2xl border border-white/10;
}

.checkin-card {
    @apply glass-card p-4 transition-all duration-200 active:scale-[0.98];
}

.checkin-card-active {
    @apply bg-emerald-500/20 border-emerald-500/30;
}

.checkin-card-mini {
    @apply glass-card p-3 flex flex-col items-center justify-center text-center relative transition-all duration-200 active:scale-[0.98] min-h-[85px];
}

.checkin-card-mini-active {
    @apply bg-amber-500/20 border-amber-500/30;
}
</style>
