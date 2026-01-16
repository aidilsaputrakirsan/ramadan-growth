<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateAvatarForm from './Partials/UpdateAvatarForm.vue';
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
            
            <!-- Avatar Section -->
            <div class="glass-card p-5">
                <UpdateAvatarForm />
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
