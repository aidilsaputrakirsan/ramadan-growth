<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center px-4 py-12 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50"
    >
        <!-- Decorative Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-200/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-teal-200/30 rounded-full blur-3xl"></div>
        </div>

        <!-- Logo & Branding -->
        <div class="relative z-10 text-center mb-8">
            <Link href="/" class="inline-block">
                <div class="text-6xl mb-3">🕌</div>
            </Link>
            <h1 class="text-2xl font-bold text-gray-800">Ramadan Growth</h1>
            <p class="text-sm text-gray-500 mt-1">Fastabiqul Khairat 🌙</p>
        </div>

        <!-- Card Container -->
        <div
            class="relative z-10 w-full max-w-md bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 p-8"
        >
            <slot />
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-6 text-center text-xs text-gray-400">
            &copy; 2026 Ramadan Growth. Built with ❤️
        </div>
    </div>
</template>
