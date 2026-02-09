<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    user: {
        name?: string;
        avatar?: string | null;
    };
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
    showRing?: boolean;
}>(), {
    size: 'md',
    showRing: false,
});

const avatarUrl = computed(() => {
    if (props.user.avatar) {
        return props.user.avatar;
    }
    // Default: DiceBear initials
    return `https://api.dicebear.com/7.x/initials/svg?seed=${props.user.name || 'User'}&backgroundColor=7c3aed`;
});

const sizeClasses = computed(() => {
    const sizes = {
        'xs': 'w-6 h-6',
        'sm': 'w-8 h-8',
        'md': 'w-10 h-10',
        'lg': 'w-14 h-14',
        'xl': 'w-20 h-20',
    };
    return sizes[props.size];
});

const ringClasses = computed(() => {
    if (!props.showRing) return '';
    return 'ring-2 ring-violet-500/50';
});
</script>

<template>
    <div 
        class="rounded-full bg-gradient-to-br from-violet-400 to-purple-500 p-0.5 flex-shrink-0"
        :class="sizeClasses"
    >
        <img 
            :src="avatarUrl" 
            :alt="user.name"
            class="w-full h-full rounded-full bg-slate-800 object-cover"
            :class="ringClasses"
            loading="lazy"
        />
    </div>
</template>
