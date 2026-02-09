<script setup lang="ts">
import { ref, computed } from 'vue';

const props = defineProps<{
    modelValue: string | null;
    userName?: string;
}>();

const emit = defineEmits(['update:modelValue']);

const showPicker = ref(false);

// Avatar styles dari DiceBear - pilih yang lebih netral/universal
const avatarStyles = [
    { id: 'lorelei', name: 'Lorelei' },
    { id: 'lorelei-neutral', name: 'Lorelei Netral' },
    { id: 'notionists', name: 'Notionists' },
    { id: 'notionists-neutral', name: 'Notionists Netral' },
    { id: 'micah', name: 'Micah' },
    { id: 'miniavs', name: 'Mini Avatars' },
    { id: 'bottts', name: 'Robots' },
    { id: 'bottts-neutral', name: 'Robots Netral' },
    { id: 'fun-emoji', name: 'Fun Emoji' },
    { id: 'thumbs', name: 'Thumbs' },
    { id: 'shapes', name: 'Shapes' },
    { id: 'glass', name: 'Glass' },
    { id: 'rings', name: 'Rings' },
    { id: 'pixel-art', name: 'Pixel Art' },
    { id: 'pixel-art-neutral', name: 'Pixel Netral' },
    { id: 'identicon', name: 'Identicon' },
];

// Generate beberapa variasi untuk setiap style
const avatarVariants = computed(() => {
    const variants: { style: string; seed: string; url: string }[] = [];
    const seeds = ['happy', 'cool', 'smile', 'star', 'moon', 'sun', 'love', 'peace'];
    
    avatarStyles.forEach(style => {
        seeds.forEach(seed => {
            variants.push({
                style: style.id,
                seed: seed,
                url: `https://api.dicebear.com/7.x/${style.id}/svg?seed=${seed}&backgroundColor=transparent`
            });
        });
    });
    
    return variants;
});

// Current avatar URL
const currentAvatarUrl = computed(() => {
    if (props.modelValue) {
        return props.modelValue;
    }
    // Default: generate dari nama user
    return `https://api.dicebear.com/7.x/initials/svg?seed=${props.userName || 'User'}&backgroundColor=7c3aed`;
});

const selectAvatar = (url: string) => {
    emit('update:modelValue', url);
    showPicker.value = false;
};

const clearAvatar = () => {
    emit('update:modelValue', null);
    showPicker.value = false;
};

// Filter by style
const selectedStyle = ref<string | null>(null);

const filteredAvatars = computed(() => {
    if (!selectedStyle.value) {
        // Show 2 dari setiap style
        const result: typeof avatarVariants.value = [];
        avatarStyles.forEach(style => {
            const styleAvatars = avatarVariants.value.filter(a => a.style === style.id);
            result.push(...styleAvatars.slice(0, 2));
        });
        return result;
    }
    return avatarVariants.value.filter(a => a.style === selectedStyle.value);
});
</script>

<template>
    <div>
        <!-- Current Avatar Display -->
        <div class="flex items-center gap-4">
            <div 
                class="w-20 h-20 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 p-0.5 cursor-pointer hover:scale-105 transition-transform"
                @click="showPicker = true"
            >
                <img 
                    :src="currentAvatarUrl" 
                    :alt="userName"
                    class="w-full h-full rounded-full bg-slate-800 object-cover"
                />
            </div>
            <div>
                <button 
                    @click="showPicker = true"
                    class="text-sm text-violet-400 hover:text-violet-300 font-medium flex items-center gap-2"
                >
                    <lord-icon
                        src="https://cdn.lordicon.com/wloilxuq.json"
                        trigger="hover"
                        colors="primary:#a78bfa"
                        style="width:18px;height:18px">
                    </lord-icon>
                    Ganti Avatar
                </button>
                <p class="text-xs text-gray-500 mt-1">Klik untuk pilih avatar</p>
            </div>
        </div>

        <!-- Avatar Picker Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showPicker" class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center">
                    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showPicker = false"></div>
                    
                    <div class="relative bg-slate-900 border border-white/10 rounded-t-3xl sm:rounded-2xl w-full sm:max-w-lg max-h-[85vh] flex flex-col">
                        <!-- Header -->
                        <div class="p-4 border-b border-white/10 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <lord-icon
                                    src="https://cdn.lordicon.com/dxjqoygy.json"
                                    trigger="loop"
                                    delay="2000"
                                    colors="primary:#a78bfa"
                                    style="width:24px;height:24px">
                                </lord-icon>
                                Pilih Avatar
                            </h3>
                            <button @click="showPicker = false" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Style Filter -->
                        <div class="p-3 border-b border-white/10 overflow-x-auto">
                            <div class="flex gap-2">
                                <button 
                                    @click="selectedStyle = null"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-all"
                                    :class="!selectedStyle ? 'bg-violet-500 text-white' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                                >
                                    Semua
                                </button>
                                <button 
                                    v-for="style in avatarStyles"
                                    :key="style.id"
                                    @click="selectedStyle = style.id"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-all"
                                    :class="selectedStyle === style.id ? 'bg-violet-500 text-white' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                                >
                                    {{ style.name }}
                                </button>
                            </div>
                        </div>

                        <!-- Avatar Grid -->
                        <div class="flex-1 overflow-y-auto p-4">
                            <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                                <button
                                    v-for="avatar in filteredAvatars"
                                    :key="`${avatar.style}-${avatar.seed}`"
                                    @click="selectAvatar(avatar.url)"
                                    class="aspect-square rounded-xl bg-white/5 hover:bg-white/15 border-2 transition-all p-2 active:scale-95"
                                    :class="modelValue === avatar.url ? 'border-violet-500 bg-violet-500/20' : 'border-transparent'"
                                >
                                    <img :src="avatar.url" :alt="avatar.seed" class="w-full h-full" loading="lazy" />
                                </button>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="p-4 border-t border-white/10 flex gap-3">
                            <button 
                                @click="clearAvatar"
                                class="flex-1 py-3 bg-white/10 text-gray-300 rounded-xl hover:bg-white/20 font-medium text-sm transition-all active:scale-[0.98]"
                            >
                                Reset ke Default
                            </button>
                            <button 
                                @click="showPicker = false"
                                class="flex-1 py-3 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-xl hover:from-violet-500 hover:to-indigo-500 font-medium text-sm transition-all active:scale-[0.98]"
                            >
                                Selesai
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
