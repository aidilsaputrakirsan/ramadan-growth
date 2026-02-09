<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

interface Particle {
    x: number;
    y: number;
    size: number;
    speedX: number;
    speedY: number;
    opacity: number;
    twinkleSpeed: number;
    twinkleDirection: number;
}

const canvas = ref<HTMLCanvasElement | null>(null);
let ctx: CanvasRenderingContext2D | null = null;
let particles: Particle[] = [];
let animationId: number | null = null;
let width = 0;
let height = 0;

// Multi-color palette for warm gradient theme
const starColors = [
    { fill: '#c084fc', glow: 'rgba(192, 132, 252, 0.6)', inner: '#e9d5ff' },  // purple-400
    { fill: '#f472b6', glow: 'rgba(244, 114, 182, 0.6)', inner: '#fce7f3' },  // pink-400
    { fill: '#38bdf8', glow: 'rgba(56, 189, 248, 0.6)', inner: '#e0f2fe' },   // sky-400
    { fill: '#fbbf24', glow: 'rgba(251, 191, 36, 0.6)', inner: '#fef3c7' },   // amber-400
    { fill: '#34d399', glow: 'rgba(52, 211, 153, 0.6)', inner: '#d1fae5' },   // emerald-400
    { fill: '#fb923c', glow: 'rgba(251, 146, 60, 0.6)', inner: '#ffedd5' },   // orange-400
];

// Jumlah particle disesuaikan untuk mobile (lebih sedikit = lebih ringan)
const getParticleCount = () => {
    const isMobile = window.innerWidth < 768;
    return isMobile ? 30 : 50;
};

const createParticle = (): Particle => ({
    x: Math.random() * width,
    y: Math.random() * height,
    size: Math.random() * 2 + 1,
    speedX: (Math.random() - 0.5) * 0.3,
    speedY: (Math.random() - 0.5) * 0.3,
    opacity: Math.random() * 0.5 + 0.3,
    twinkleSpeed: Math.random() * 0.02 + 0.01,
    twinkleDirection: 1,
});

const initParticles = () => {
    particles = [];
    const count = getParticleCount();
    for (let i = 0; i < count; i++) {
        particles.push(createParticle());
    }
};

const drawStar = (x: number, y: number, size: number, opacity: number, index: number) => {
    if (!ctx) return;

    const colorSet = starColors[index % starColors.length];

    ctx.save();
    ctx.globalAlpha = opacity;

    // Glow effect
    ctx.shadowBlur = size * 3;
    ctx.shadowColor = colorSet.glow;

    // Star shape
    ctx.fillStyle = colorSet.fill;
    ctx.beginPath();
    for (let i = 0; i < 5; i++) {
        const angle = (i * 4 * Math.PI) / 5 - Math.PI / 2;
        const x1 = x + Math.cos(angle) * size;
        const y1 = y + Math.sin(angle) * size;
        if (i === 0) {
            ctx.moveTo(x1, y1);
        } else {
            ctx.lineTo(x1, y1);
        }
    }
    ctx.closePath();
    ctx.fill();

    // Inner glow
    ctx.beginPath();
    ctx.arc(x, y, size * 0.5, 0, Math.PI * 2);
    ctx.fillStyle = colorSet.inner;
    ctx.fill();

    ctx.restore();
};

const drawCrescent = () => {
    if (!ctx) return;

    const x = width * 0.85;
    const y = height * 0.15;
    const radius = Math.min(width, height) * 0.08;

    ctx.save();
    ctx.globalAlpha = 0.15;

    // Outer glow - purple
    ctx.shadowBlur = 30;
    ctx.shadowColor = 'rgba(192, 132, 252, 0.5)';

    // Crescent moon - purple
    ctx.beginPath();
    ctx.arc(x, y, radius, 0, Math.PI * 2);
    ctx.fillStyle = '#c084fc';
    ctx.fill();

    // Cut out inner circle to create crescent
    ctx.globalCompositeOperation = 'destination-out';
    ctx.beginPath();
    ctx.arc(x + radius * 0.35, y - radius * 0.1, radius * 0.85, 0, Math.PI * 2);
    ctx.fill();

    ctx.restore();
};

const animate = () => {
    if (!ctx || !canvas.value) return;

    ctx.clearRect(0, 0, width, height);

    // Draw crescent moon
    drawCrescent();

    // Update and draw particles
    particles.forEach((particle, index) => {
        // Update position
        particle.x += particle.speedX;
        particle.y += particle.speedY;

        // Twinkle effect
        particle.opacity += particle.twinkleSpeed * particle.twinkleDirection;
        if (particle.opacity >= 0.8 || particle.opacity <= 0.2) {
            particle.twinkleDirection *= -1;
        }

        // Wrap around edges
        if (particle.x < 0) particle.x = width;
        if (particle.x > width) particle.x = 0;
        if (particle.y < 0) particle.y = height;
        if (particle.y > height) particle.y = 0;

        // Draw star with color based on index
        drawStar(particle.x, particle.y, particle.size, particle.opacity, index);
    });

    animationId = requestAnimationFrame(animate);
};

const handleResize = () => {
    if (!canvas.value) return;

    width = window.innerWidth;
    height = window.innerHeight;
    canvas.value.width = width;
    canvas.value.height = height;

    initParticles();
};

onMounted(() => {
    if (!canvas.value) return;

    ctx = canvas.value.getContext('2d');
    handleResize();
    animate();

    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    if (animationId) {
        cancelAnimationFrame(animationId);
    }
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <canvas
        ref="canvas"
        class="fixed inset-0 pointer-events-none z-0"
        aria-hidden="true"
    />
</template>
