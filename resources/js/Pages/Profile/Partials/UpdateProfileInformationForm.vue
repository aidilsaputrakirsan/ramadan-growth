<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="flex items-center gap-3">
            <lord-icon
                src="https://cdn.lordicon.com/wloilxuq.json"
                trigger="hover"
                colors="primary:#a78bfa"
                style="width:28px;height:28px">
            </lord-icon>
            <div>
                <h2 class="text-base font-bold text-white">Informasi Profil</h2>
                <p class="text-xs text-gray-400">Ubah nama dan email akun Anda.</p>
            </div>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-5 space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nama</label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="dark-input"
                />
                <InputError class="mt-1" :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="dark-input"
                />
                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="text-sm">
                <p class="text-gray-400">
                    Email belum diverifikasi.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="text-violet-400 underline hover:text-violet-300"
                    >
                        Kirim ulang verifikasi
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-violet-400">
                    Link verifikasi baru telah dikirim.
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button 
                    type="submit"
                    :disabled="form.processing"
                    class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-sm font-medium rounded-xl transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    Simpan
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
