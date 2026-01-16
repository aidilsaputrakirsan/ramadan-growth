<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emerald-400 text-start text-base font-medium text-emerald-400 bg-emerald-900/30 focus:outline-none focus:text-emerald-300 focus:bg-emerald-900/50 focus:border-emerald-300 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-white hover:bg-slate-700/50 hover:border-emerald-400/50 focus:outline-none focus:text-white focus:bg-slate-700/50 focus:border-emerald-400/50 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
