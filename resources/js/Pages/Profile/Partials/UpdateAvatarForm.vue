<script setup lang="ts">
import AvatarPicker from '@/Components/AvatarPicker.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const user = usePage().props.auth.user;

const form = useForm({
    avatar: user.avatar || null,
});

// Auto-save when avatar changes
watch(() => form.avatar, (newValue, oldValue) => {
    if (newValue !== oldValue) {
        form.patch(route('profile.avatar.update'), {
            preserveScroll: true,
        });
    }
});
</script>

<template>
    <section>
        <header class="flex items-center gap-3 mb-5">
            <lord-icon
                src="https://cdn.lordicon.com/dxjqoygy.json"
                trigger="hover"
                colors="primary:#34d399"
                style="width:28px;height:28px">
            </lord-icon>
            <div>
                <h2 class="text-base font-bold text-white">Avatar Profil</h2>
                <p class="text-xs text-gray-400">Pilih avatar yang mewakili kamu.</p>
            </div>
        </header>

        <AvatarPicker v-model="form.avatar" :user-name="user.name" />

        <!-- Saving indicator -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in duration-150"
            leave-to-class="opacity-0"
        >
            <div v-if="form.processing" class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                <lord-icon
                    src="https://cdn.lordicon.com/xjovhxra.json"
                    trigger="loop"
                    colors="primary:#34d399"
                    style="width:16px;height:16px">
                </lord-icon>
                Menyimpan...
            </div>
            <div v-else-if="form.recentlySuccessful" class="mt-3 flex items-center gap-2 text-xs text-emerald-400">
                <lord-icon
                    src="https://cdn.lordicon.com/egiwmiit.json"
                    trigger="in"
                    colors="primary:#34d399"
                    style="width:16px;height:16px">
                </lord-icon>
                Avatar tersimpan!
            </div>
        </Transition>
    </section>
</template>
