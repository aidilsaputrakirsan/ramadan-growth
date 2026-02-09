<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

interface AdminUser {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    perfect_days_count: number;
    created_at: string;
}

const props = defineProps<{
    users: AdminUser[];
}>();

// Edit Modal State
const showEditModal = ref(false);
const editingUser = ref<AdminUser | null>(null);
const editForm = useForm({
    name: '',
    email: '',
});

const openEditModal = (user: AdminUser) => {
    editingUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editingUser.value = null;
};

const submitEdit = () => {
    if (!editingUser.value) return;
    // @ts-ignore
    editForm.patch(route('admin.users.update', editingUser.value.id), {
        onSuccess: () => closeEditModal(),
    });
};

const resetPassword = (user: AdminUser) => {
    if (confirm(`Reset password "${user.name}" ke "password123"?`)) {
        // @ts-ignore
        useForm({}).post(route('admin.users.reset-password', user.id));
    }
};

const deleteUser = (user: AdminUser) => {
    if (confirm(`Hapus user "${user.name}"? Tindakan ini tidak bisa dibatalkan.`)) {
        // @ts-ignore
        useForm({}).delete(route('admin.users.destroy', user.id));
    }
};
</script>

<template>
    <Head title="Admin - Users" />

    <AuthenticatedLayout>
        <div class="px-4 py-6 space-y-4">
            
            <!-- Header -->
            <div class="glass-card p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <lord-icon
                            src="https://cdn.lordicon.com/lecprnjb.json"
                            trigger="loop"
                            delay="2000"
                            colors="primary:#fb923c,secondary:#f472b6"
                            style="width:40px;height:40px">
                        </lord-icon>
                        <div>
                            <h1 class="text-lg font-bold text-white">Kelola User</h1>
                            <p class="text-xs text-gray-400">Total: {{ users.length }} user</p>
                        </div>
                    </div>
                    <span class="text-[10px] bg-orange-500/20 text-orange-400 px-3 py-1 rounded-full font-bold uppercase">
                        Admin
                    </span>
                </div>
            </div>

            <!-- User List -->
            <div class="space-y-2">
                <div
                    v-for="(user, index) in users"
                    :key="user.id"
                    class="glass-card p-4"
                >
                    <div class="flex items-center gap-3">
                        <!-- Avatar -->
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ user.name?.charAt(0)?.toUpperCase() }}
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-white font-medium text-sm truncate">{{ user.name }}</span>
                                <span v-if="user.is_admin" class="text-[9px] bg-orange-500/20 text-orange-400 px-2 py-0.5 rounded-full font-bold">ADMIN</span>
                            </div>
                            <div class="text-gray-400 text-xs truncate">{{ user.email }}</div>
                        </div>
                        
                        <!-- Stats -->
                        <div class="text-right flex-shrink-0">
                            <div class="text-cyan-400 font-bold text-sm">{{ user.perfect_days_count }}</div>
                            <div class="text-gray-500 text-[10px]">hari</div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2 mt-3 pt-3 border-t border-white/5">
                        <button 
                            @click="openEditModal(user)" 
                            class="flex-1 py-2 text-xs font-medium text-blue-400 bg-blue-500/10 rounded-lg hover:bg-blue-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-1"
                        >
                            <lord-icon
                                src="https://cdn.lordicon.com/wloilxuq.json"
                                trigger="hover"
                                colors="primary:#60a5fa"
                                style="width:16px;height:16px">
                            </lord-icon>
                            Edit
                        </button>
                        <button 
                            @click="resetPassword(user)" 
                            class="flex-1 py-2 text-xs font-medium text-amber-400 bg-amber-500/10 rounded-lg hover:bg-amber-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-1"
                        >
                            <lord-icon
                                src="https://cdn.lordicon.com/pdsourfn.json"
                                trigger="hover"
                                colors="primary:#fbbf24"
                                style="width:16px;height:16px">
                            </lord-icon>
                            Reset
                        </button>
                        <button 
                            v-if="!user.is_admin"
                            @click="deleteUser(user)" 
                            class="flex-1 py-2 text-xs font-medium text-red-400 bg-red-500/10 rounded-lg hover:bg-red-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-1"
                        >
                            <lord-icon
                                src="https://cdn.lordicon.com/drxwpfop.json"
                                trigger="hover"
                                colors="primary:#f87171"
                                style="width:16px;height:16px">
                            </lord-icon>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="users.length === 0" class="glass-card p-8 text-center">
                <lord-icon
                    src="https://cdn.lordicon.com/msoeawqm.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#9ca3af"
                    style="width:64px;height:64px"
                    class="mx-auto mb-3">
                </lord-icon>
                <p class="text-gray-400">Belum ada user.</p>
            </div>

        </div>

        <!-- Edit Modal -->
        <Modal :show="showEditModal" @close="closeEditModal">
            <div class="bg-slate-900 p-6 rounded-2xl border border-white/10">
                <h3 class="text-lg font-bold text-white mb-4">Edit User</h3>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Nama</label>
                        <input 
                            v-model="editForm.name" 
                            type="text" 
                            class="dark-input" 
                        />
                        <div v-if="editForm.errors.name" class="text-red-400 text-xs mt-1">{{ editForm.errors.name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                        <input 
                            v-model="editForm.email" 
                            type="email" 
                            class="dark-input" 
                        />
                        <div v-if="editForm.errors.email" class="text-red-400 text-xs mt-1">{{ editForm.errors.email }}</div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button 
                            type="button" 
                            @click="closeEditModal" 
                            class="flex-1 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 font-medium text-sm transition-all active:scale-[0.98]"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            :disabled="editForm.processing" 
                            class="flex-1 py-3 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white rounded-xl font-medium text-sm transition-all active:scale-[0.98] disabled:opacity-50"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.glass-card {
    @apply bg-white/10 backdrop-blur-md rounded-2xl border border-white/10;
}

.dark-input {
    @apply w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500/50 transition-all;
}
</style>
