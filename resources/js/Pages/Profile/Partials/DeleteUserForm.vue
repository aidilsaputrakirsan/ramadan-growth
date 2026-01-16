<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <header class="flex items-center gap-3">
            <lord-icon
                src="https://cdn.lordicon.com/drxwpfop.json"
                trigger="hover"
                colors="primary:#f87171"
                style="width:28px;height:28px">
            </lord-icon>
            <div>
                <h2 class="text-base font-bold text-red-400">Hapus Akun</h2>
                <p class="text-xs text-gray-400">Data akan hilang permanen.</p>
            </div>
        </header>

        <button 
            @click="confirmUserDeletion" 
            class="mt-4 px-5 py-2.5 bg-red-600/20 hover:bg-red-600/30 text-red-400 text-sm font-medium rounded-xl border border-red-500/30 transition-all active:scale-[0.98]"
        >
            Hapus Akun
        </button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="bg-slate-900 p-6 rounded-2xl border border-white/10">
                <lord-icon
                    src="https://cdn.lordicon.com/drxwpfop.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#f87171"
                    style="width:56px;height:56px"
                    class="mx-auto mb-3">
                </lord-icon>
                <h2 class="text-lg font-bold text-white text-center">Yakin hapus akun?</h2>
                <p class="mt-2 text-sm text-gray-400 text-center">
                    Tindakan ini tidak dapat dibatalkan. Masukkan password untuk konfirmasi.
                </p>

                <div class="mt-4">
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        placeholder="Masukkan password"
                        class="dark-input"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-1" />
                </div>

                <div class="mt-6 flex gap-3">
                    <button 
                        @click="closeModal" 
                        class="flex-1 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 font-medium text-sm transition-all active:scale-[0.98]"
                    >
                        Batal
                    </button>
                    <button
                        @click="deleteUser"
                        :disabled="form.processing"
                        class="flex-1 py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl font-medium text-sm transition-all active:scale-[0.98] disabled:opacity-50"
                    >
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>

<style scoped>
.dark-input {
    @apply w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500/50 transition-all;
}
</style>
