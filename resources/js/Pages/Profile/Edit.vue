<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { PageProps } from '@/types';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();

const page = usePage<PageProps>();
const isAdmin = page.props.auth.user.is_admin ?? false;
</script>

<template>
    <Head title="Profil" />

    <AuthenticatedLayout>
        <div class="px-4 py-6 space-y-4">
            
            <!-- Profile Header -->
            <div class="glass-card p-6 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg shadow-emerald-500/30 relative">
                    <span class="text-3xl text-white font-bold">{{ $page.props.auth.user.name?.charAt(0)?.toUpperCase() }}</span>
                    <div class="absolute -bottom-1 -right-1">
                        <lord-icon
                            src="https://cdn.lordicon.com/hbvgknxo.json"
                            trigger="loop"
                            delay="3000"
                            colors="primary:#34d399"
                            style="width:24px;height:24px">
                        </lord-icon>
                    </div>
                </div>
                <h1 class="text-xl font-bold text-white">{{ $page.props.auth.user.name }}</h1>
                <p class="text-gray-400 text-sm">{{ $page.props.auth.user.email }}</p>
            </div>

            <!-- Update Profile -->
            <div class="glass-card p-5">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>

            <!-- Update Password -->
            <div class="glass-card p-5">
                <UpdatePasswordForm />
            </div>

            <!-- Delete Account (Admin only) -->
            <div v-if="isAdmin" class="glass-card p-5 border-red-500/20">
                <DeleteUserForm />
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.glass-card {
    @apply bg-white/10 backdrop-blur-md rounded-2xl border border-white/10;
}
</style>
