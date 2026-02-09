<script setup lang="ts">
/**
 * Optimized Background Component
 * Uses CSS Animations instead of Canvas/JS for maximum performance on mobile (iPhone 13, etc.)
 */
</script>

<template>
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none bg-slate-950" aria-hidden="true">
        <!-- Mesh Gradient Layers -->
        <div class="absolute inset-0 opacity-40">
            <div class="mesh-blob blob-1"></div>
            <div class="mesh-blob blob-2"></div>
            <div class="mesh-blob blob-3"></div>
        </div>

        <!-- Subtle Stars (Static/CSS) -->
        <div class="absolute inset-0 stars-overlay opacity-30"></div>

        <!-- Crescent Moon (Pure CSS/SVG) -->
        <div class="absolute top-[10%] right-[10%] opacity-20">
            <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="40" fill="#c084fc" />
                <circle cx="65" cy="40" r="35" fill="rgb(2 6 23)" />
            </svg>
        </div>
    </div>
</template>

<style scoped>
.mesh-blob {
    @apply absolute rounded-full blur-[80px] md:blur-[120px] mix-blend-screen animate-mesh;
}

.blob-1 {
    width: 60%;
    height: 50%;
    background: radial-gradient(circle, rgba(192, 132, 252, 0.4) 0%, transparent 70%);
    top: -10%;
    left: -10%;
    animation-duration: 25s;
}

.blob-2 {
    width: 50%;
    height: 60%;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);
    bottom: -10%;
    right: -10%;
    animation-duration: 20s;
    animation-delay: -5s;
}

.blob-3 {
    width: 40%;
    height: 40%;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.3) 0%, transparent 70%);
    top: 30%;
    left: 40%;
    animation-duration: 15s;
    animation-delay: -2s;
}

@keyframes mesh {
    0% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(10%, 15%) scale(1.1); }
    66% { transform: translate(-5%, 20%) scale(0.9); }
    100% { transform: translate(0, 0) scale(1); }
}

.animate-mesh {
    animation: mesh infinite linear alternate;
}

.stars-overlay {
    background-image: 
        radial-gradient(1px 1px at 20px 30px, #ffffff, rgba(0,0,0,0)),
        radial-gradient(1px 1px at 40px 70px, #ffffff, rgba(0,0,0,0)),
        radial-gradient(1.5px 1.5px at 50px 160px, #ffffff, rgba(0,0,0,0)),
        radial-gradient(1px 1px at 80px 120px, #ffffff, rgba(0,0,0,0)),
        radial-gradient(1px 1px at 110px 40px, #ffffff, rgba(0,0,0,0)),
        radial-gradient(1.5px 1.5px at 150px 150px, #ffffff, rgba(0,0,0,0));
    background-size: 200px 200px;
    background-repeat: repeat;
}
</style>
