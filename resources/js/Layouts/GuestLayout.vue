<script setup lang="ts">
import RamadanLogo from '@/Components/RamadanLogo.vue';
import ParticleBackground from '@/Components/ParticleBackground.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center px-4 py-12 bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900"
    >
        <!-- Particle Background -->
        <ParticleBackground />
        
        <!-- Decorative Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Logo & Branding -->
        <div class="relative z-10 text-center mb-8">
            <Link href="/" class="inline-block mb-3">
                <RamadanLogo size="md" :animated="true" />
            </Link>
            <h1 class="text-2xl font-bold text-white">Ramadan Tracker</h1>
            <p class="text-sm text-emerald-300/80 mt-1">Fastabiqul Khairat</p>
        </div>

        <!-- Card Container -->
        <div
            class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 p-8"
        >
            <slot />
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-6 text-center text-xs text-emerald-300/60">
            &copy; 2026 Ramadan Tracker. Built with ❤️
        </div>
    </div>
</template>
