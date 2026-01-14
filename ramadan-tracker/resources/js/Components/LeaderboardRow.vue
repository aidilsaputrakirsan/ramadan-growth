<script setup lang="ts">
import { LeaderboardUser } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    rank: number;
    user: LeaderboardUser;
    isCurrentUser: boolean;
}>();

const rankClasses = computed(() => {
    switch (props.rank) {
        case 1: return 'bg-yellow-50 text-yellow-700 border-yellow-200';
        case 2: return 'bg-slate-50 text-slate-700 border-slate-200';
        case 3: return 'bg-orange-50 text-orange-800 border-orange-200';
        default: return 'bg-transparent text-gray-500 border-transparent';
    }
});

const medal = computed(() => {
    switch (props.rank) {
        case 1: return '👑';
        case 2: return '🥈';
        case 3: return '🥉';
        default: return props.rank;
    }
});

const stageIcon = computed(() => {
    switch (props.user.masjid_stage) {
        case 1: return '🏗️';
        case 2: return '🧱';
        case 3: return '🕌';
        case 4: return '✨';
        default: return '❓';
    }
});
</script>

<template>
    <div 
        class="flex items-center gap-4 p-3 sm:p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden"
        :class="[
            isCurrentUser ? 'bg-emerald-50/50 border-emerald-500 shadow-md z-10' : 'bg-white border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-100',
            rank <= 3 ? 'mb-4 py-6' : 'mb-2'
        ]"
    >
        <!-- Top 3 Background Decoration -->
        <div v-if="rank === 1" class="absolute -right-4 -top-4 text-9xl opacity-5 select-none pointer-events-none rotate-12">🏆</div>
        <div v-if="rank === 2" class="absolute -right-4 -top-4 text-8xl opacity-5 select-none pointer-events-none rotate-12">🥈</div>
        <div v-if="rank === 3" class="absolute -right-4 -top-4 text-8xl opacity-5 select-none pointer-events-none rotate-12">🥉</div>

        <!-- Rank -->
        <div 
            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-xl font-black text-lg sm:text-xl border-2 shadow-sm"
            :class="rankClasses"
        >
            {{ medal }}
        </div>

        <!-- User Info -->
        <div class="flex-grow min-w-0 z-0">
            <div class="flex items-center gap-2 mb-0.5">
                <h3 class="font-bold text-gray-800 truncate text-sm sm:text-base leading-tight" :class="{ 'text-emerald-800': isCurrentUser }">
                    {{ user.name }}
                </h3>
                <span v-if="isCurrentUser" class="text-[9px] uppercase font-bold bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full shadow-sm">You</span>
            </div>
            <div class="text-xs text-gray-500 flex items-center gap-1.5 font-medium">
                <span class="bg-gray-100 px-1.5 py-0.5 rounded text-[10px] tracking-wide text-gray-600 uppercase">Stage {{ user.masjid_stage }}</span>
                <span>{{ stageIcon }}</span>
            </div>
        </div>

        <!-- Score -->
        <div class="text-right flex-shrink-0 z-0">
            <div class="font-black text-2xl sm:text-3xl text-emerald-600 leading-none tracking-tight">
                {{ user.perfect_days_count }}
            </div>
            <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">
                Perfect Days
            </div>
        </div>
    </div>
</template>
