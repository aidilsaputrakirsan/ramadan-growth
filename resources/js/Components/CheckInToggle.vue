<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: boolean;
    label: string;
    icon?: string;
    description?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const toggle = () => {
    emit('update:modelValue', !props.modelValue);
};

const containerClasses = computed(() => {
    return props.modelValue
        ? 'bg-emerald-50 border-emerald-500 shadow-md'
        : 'bg-white border-gray-200 shadow-sm hover:border-emerald-200';
});

const textClasses = computed(() => {
    return props.modelValue ? 'text-emerald-900' : 'text-gray-600';
});
</script>

<template>
    <button
        type="button"
        @click="toggle"
        class="w-full flex items-center justify-between p-4 rounded-xl border-2 transition-all duration-200 ease-out active:scale-[0.98] group"
        :class="containerClasses"
    >
        <div class="flex items-center gap-4 text-left">
            <div 
                v-if="icon" 
                class="w-10 h-10 flex items-center justify-center rounded-lg text-2xl bg-white shadow-sm transition-transform duration-200 group-hover:scale-110"
            >
                {{ icon }}
            </div>
            <div>
                <h3 class="font-bold text-base" :class="textClasses">{{ label }}</h3>
                <p v-if="description" class="text-xs text-gray-500 mt-0.5">{{ description }}</p>
            </div>
        </div>

        <div class="relative w-12 h-7 rounded-full transition-colors duration-300"
             :class="modelValue ? 'bg-emerald-200' : 'bg-gray-200'"
        >
            <div 
                class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow-sm transform transition-transform duration-300"
                :class="modelValue ? 'translate-x-5 !bg-emerald-500' : 'translate-x-0'"
            ></div>
        </div>
    </button>
</template>
