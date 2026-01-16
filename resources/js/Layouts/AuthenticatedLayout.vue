<script setup lang="ts">
import { ref } from 'vue';
import RamadanLogo from '@/Components/RamadanLogo.vue';
import ParticleBackground from '@/Components/ParticleBackground.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const showLogoutConfirm = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 relative">
        <!-- Particle Background -->
        <ParticleBackground />
        
        <!-- Decorative Blurs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 z-50 sm:hidden">
            <div class="bg-slate-900/95 backdrop-blur-xl border-t border-white/10 px-2 py-2 safe-area-bottom">
                <div class="flex justify-around items-center">
                    <Link 
                        :href="route('dashboard')" 
                        class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all"
                        :class="route().current('dashboard') 
                            ? 'text-emerald-400 bg-emerald-500/20' 
                            : 'text-gray-400'"
                    >
                        <lord-icon
                            src="https://cdn.lordicon.com/wmwqvixz.json"
                            trigger="hover"
                            :colors="route().current('dashboard') ? 'primary:#34d399' : 'primary:#9ca3af'"
                            style="width:24px;height:24px">
                        </lord-icon>
                        <span class="text-[10px] font-medium">Home</span>
                    </Link>
                    <Link 
                        :href="route('community')" 
                        class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all"
                        :class="route().current('community') 
                            ? 'text-emerald-400 bg-emerald-500/20' 
                            : 'text-gray-400'"
                    >
                        <lord-icon
                            src="https://cdn.lordicon.com/oqdmuxru.json"
                            trigger="hover"
                            :colors="route().current('community') ? 'primary:#34d399' : 'primary:#9ca3af'"
                            style="width:24px;height:24px">
                        </lord-icon>
                        <span class="text-[10px] font-medium">Ranking</span>
                    </Link>
                    <Link 
                        v-if="$page.props.auth.user.is_admin"
                        :href="route('admin.users.index')" 
                        class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all"
                        :class="route().current('admin.users.*') 
                            ? 'text-amber-400 bg-amber-500/20' 
                            : 'text-gray-400'"
                    >
                        <lord-icon
                            src="https://cdn.lordicon.com/hwuyodym.json"
                            trigger="hover"
                            :colors="route().current('admin.users.*') ? 'primary:#fbbf24' : 'primary:#9ca3af'"
                            style="width:24px;height:24px">
                        </lord-icon>
                        <span class="text-[10px] font-medium">Admin</span>
                    </Link>
                    <Link 
                        :href="route('profile.edit')" 
                        class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all"
                        :class="route().current('profile.edit') 
                            ? 'text-emerald-400 bg-emerald-500/20' 
                            : 'text-gray-400'"
                    >
                        <lord-icon
                            src="https://cdn.lordicon.com/kthelypq.json"
                            trigger="hover"
                            :colors="route().current('profile.edit') ? 'primary:#34d399' : 'primary:#9ca3af'"
                            style="width:24px;height:24px">
                        </lord-icon>
                        <span class="text-[10px] font-medium">Profil</span>
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Desktop Top Navigation -->
        <nav class="hidden sm:block sticky top-0 z-50 bg-slate-900/80 backdrop-blur-xl border-b border-white/10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <RamadanLogo size="sm" :animated="true" class="!w-10 !h-10" />
                        <span class="font-bold text-white">Ramadan Growth</span>
                    </Link>

                    <!-- Desktop Nav Links -->
                    <div class="flex items-center gap-1">
                        <Link 
                            :href="route('dashboard')"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all"
                            :class="route().current('dashboard') 
                                ? 'text-emerald-400 bg-emerald-500/20' 
                                : 'text-gray-300 hover:text-white hover:bg-white/5'"
                        >
                            <lord-icon
                                src="https://cdn.lordicon.com/wmwqvixz.json"
                                trigger="hover"
                                colors="primary:#34d399"
                                style="width:20px;height:20px">
                            </lord-icon>
                            Dashboard
                        </Link>
                        <Link 
                            :href="route('community')"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all"
                            :class="route().current('community') 
                                ? 'text-emerald-400 bg-emerald-500/20' 
                                : 'text-gray-300 hover:text-white hover:bg-white/5'"
                        >
                            <lord-icon
                                src="https://cdn.lordicon.com/oqdmuxru.json"
                                trigger="hover"
                                colors="primary:#34d399"
                                style="width:20px;height:20px">
                            </lord-icon>
                            Leaderboard
                        </Link>
                        <Link 
                            v-if="$page.props.auth.user.is_admin"
                            :href="route('admin.users.index')"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all"
                            :class="route().current('admin.users.*') 
                                ? 'text-amber-400 bg-amber-500/20' 
                                : 'text-amber-400/70 hover:text-amber-400 hover:bg-amber-500/10'"
                        >
                            <lord-icon
                                src="https://cdn.lordicon.com/hwuyodym.json"
                                trigger="hover"
                                colors="primary:#fbbf24"
                                style="width:20px;height:20px">
                            </lord-icon>
                            Admin
                        </Link>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-3">
                        <Link 
                            :href="route('profile.edit')"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all"
                        >
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-sm font-bold">
                                {{ $page.props.auth.user.name?.charAt(0)?.toUpperCase() }}
                            </div>
                            <span class="hidden lg:inline">{{ $page.props.auth.user.name }}</span>
                        </Link>
                        <button 
                            @click="showLogoutConfirm = true"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all"
                        >
                            <lord-icon
                                src="https://cdn.lordicon.com/moscwhoj.json"
                                trigger="hover"
                                colors="primary:#f87171"
                                style="width:20px;height:20px">
                            </lord-icon>
                            <span class="hidden lg:inline">Keluar</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Header -->
        <header class="sm:hidden sticky top-0 z-40 bg-slate-900/90 backdrop-blur-xl border-b border-white/10">
            <div class="flex items-center justify-between px-4 py-3">
                <Link :href="route('dashboard')" class="flex items-center gap-2">
                    <RamadanLogo size="sm" :animated="true" class="!w-8 !h-8" />
                    <span class="font-bold text-white text-sm">Ramadan Growth</span>
                </Link>
                <button 
                    @click="showLogoutConfirm = true"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-400 hover:text-red-400 bg-white/5 hover:bg-red-500/10 transition-all"
                >
                    <lord-icon
                        src="https://cdn.lordicon.com/moscwhoj.json"
                        trigger="hover"
                        colors="primary:#f87171"
                        style="width:16px;height:16px">
                    </lord-icon>
                    Keluar
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative z-10 pb-24 sm:pb-8">
            <slot />
        </main>

        <!-- Logout Confirmation Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showLogoutConfirm" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showLogoutConfirm = false"></div>
                    <div class="relative bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-sm text-center">
                        <lord-icon
                            src="https://cdn.lordicon.com/moscwhoj.json"
                            trigger="loop"
                            delay="1000"
                            colors="primary:#f87171"
                            style="width:64px;height:64px"
                            class="mx-auto mb-4">
                        </lord-icon>
                        <h3 class="text-lg font-bold text-white mb-2">Yakin mau keluar?</h3>
                        <p class="text-gray-400 text-sm mb-6">Kamu akan keluar dari akun ini.</p>
                        <div class="flex gap-3">
                            <button 
                                @click="showLogoutConfirm = false"
                                class="flex-1 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 font-medium text-sm transition-all active:scale-[0.98]"
                            >
                                Batal
                            </button>
                            <Link 
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="flex-1 py-3 bg-red-600 hover:bg-red-500 text-white rounded-xl font-medium text-sm transition-all active:scale-[0.98]"
                            >
                                Ya, Keluar
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.safe-area-bottom {
    padding-bottom: env(safe-area-inset-bottom, 8px);
}
</style>
