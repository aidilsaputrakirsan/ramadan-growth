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
    <Head title="Admin - Kelola User" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-gray-800 flex items-center gap-2">
                    <span>⚙️</span> Kelola User
                </h2>
                <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold uppercase">
                    Admin Panel
                </span>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perfect Days</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Joined</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="(user, index) in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-800">{{ user.name }}</div>
                                        <span v-if="user.is_admin" class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">ADMIN</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-emerald-600 font-bold">{{ user.perfect_days_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ user.created_at }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <button @click="openEditModal(user)" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                        <button @click="resetPassword(user)" class="text-orange-600 hover:text-orange-800 font-medium">Reset PW</button>
                                        <button v-if="!user.is_admin" @click="deleteUser(user)" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4 text-center text-xs text-gray-400">
                    Total: {{ users.length }} user
                </div>

            </div>
        </div>

        <!-- Edit Modal -->
        <Modal :show="showEditModal" @close="closeEditModal">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Edit User</h3>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input v-model="editForm.name" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500" />
                        <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="editForm.email" type="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500" />
                        <div v-if="editForm.errors.email" class="text-red-500 text-xs mt-1">{{ editForm.errors.email }}</div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm">Batal</button>
                        <button type="submit" :disabled="editForm.processing" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium text-sm disabled:opacity-50">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
