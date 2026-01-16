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

const drawStar = (x: number, y: number, size: number, opacity: number) => {
    if (!ctx) return;
    
    ctx.save();
    ctx.globalAlpha = opacity;
    
    // Glow effect
    ctx.shadowBlur = size * 3;
    ctx.shadowColor = 'rgba(251, 191, 36, 0.6)';
    
    // Star shape
    ctx.fillStyle = '#fbbf24';
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
    ctx.fillStyle = '#fef3c7';
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
    
    // Outer glow
    ctx.shadowBlur = 30;
    ctx.shadowColor = 'rgba(251, 191, 36, 0.5)';
    
    // Crescent moon
    ctx.beginPath();
    ctx.arc(x, y, radius, 0, Math.PI * 2);
    ctx.fillStyle = '#fbbf24';
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
    particles.forEach((particle) => {
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
        
        // Draw star
        drawStar(particle.x, particle.y, particle.size, particle.opacity);
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
