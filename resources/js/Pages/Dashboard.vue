<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { PageProps } from '@/types';
import { watch, computed } from 'vue';

const page = usePage<PageProps>();

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

// Initialize form
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

// Auto-save
const autoSave = () => {
    form.post(route('daily-log.store'), {
        preserveScroll: true,
        preserveState: true,
    });
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

const toggleTask = (key: string) => {
    form.tasks_completed[key] = !form.tasks_completed[key];
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="px-4 py-6 space-y-4">
            
            <!-- Greeting Card -->
            <div class="glass-card p-5 text-center">
                <p class="text-emerald-400 text-sm font-medium mb-1">Assalamu'alaikum 👋</p>
                <h1 class="text-white text-xl font-bold">{{ $page.props.auth.user.name }}</h1>
                <p class="text-gray-400 text-xs mt-1">
                    {{ new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' }) }}
                </p>
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
                >
                    <span v-if="form.processing" class="text-xs text-gray-400 flex items-center gap-2">
                        <lord-icon
                            src="https://cdn.lordicon.com/xjovhxra.json"
                            trigger="loop"
                            colors="primary:#34d399"
                            style="width:16px;height:16px">
                        </lord-icon>
                        Menyimpan...
                    </span>
                    <span v-else-if="form.recentlySuccessful" class="text-xs text-emerald-400 flex items-center gap-1">
                        <lord-icon
                            src="https://cdn.lordicon.com/oqdmuxru.json"
                            trigger="in"
                            colors="primary:#34d399"
                            style="width:16px;height:16px">
                        </lord-icon>
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
