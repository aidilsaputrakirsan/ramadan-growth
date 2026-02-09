<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="flex items-center gap-3">
            <lord-icon
                src="https://cdn.lordicon.com/pdsourfn.json"
                trigger="hover"
                colors="primary:#a78bfa"
                style="width:28px;height:28px">
            </lord-icon>
            <div>
                <h2 class="text-base font-bold text-white">Perbarui Password</h2>
                <p class="text-xs text-gray-400">Gunakan password yang kuat.</p>
            </div>
        </header>

        <form @submit.prevent="updatePassword" class="mt-5 space-y-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-300 mb-1">Password Saat Ini</label>
                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    autocomplete="current-password"
                    class="dark-input"
                />
                <InputError :message="form.errors.current_password" class="mt-1" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password Baru</label>
                <input
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    class="dark-input"
                />
                <InputError :message="form.errors.password" class="mt-1" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Konfirmasi Password</label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="dark-input"
                />
                <InputError :message="form.errors.password_confirmation" class="mt-1" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button 
                    type="submit"
                    :disabled="form.processing"
                    class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-sm font-medium rounded-xl transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    Update Password
                </button>
                <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in duration-150"
                    leave-to-class="opacity-0"
                >
                    <span v-if="form.recentlySuccessful" class="text-sm text-violet-400">✓ Tersimpan</span>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
.dark-input {
    @apply w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500/50 transition-all;
}
</style>
