<script setup lang="ts">
defineProps<{
    size?: 'sm' | 'md' | 'lg';
    animated?: boolean;
}>();
</script>

<template>
    <svg 
        :class="{
            'w-16 h-16': size === 'sm',
            'w-24 h-24': size === 'md' || !size,
            'w-32 h-32': size === 'lg'
        }"
        viewBox="0 0 64 64" 
        xmlns="http://www.w3.org/2000/svg"
    >
        <defs>
            <!-- Gradient untuk kubah masjid -->
            <linearGradient id="domeGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#34d399"/>
                <stop offset="100%" style="stop-color:#059669"/>
            </linearGradient>
            
            <!-- Gradient untuk bulan -->
            <linearGradient id="moonGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#fcd34d"/>
                <stop offset="100%" style="stop-color:#f59e0b"/>
            </linearGradient>
            
            <!-- Glow filter -->
            <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stdDeviation="1.5" result="coloredBlur"/>
                <feMerge>
                    <feMergeNode in="coloredBlur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
            
            <!-- Star glow -->
            <filter id="starGlow" x="-100%" y="-100%" width="300%" height="300%">
                <feGaussianBlur stdDeviation="0.5" result="blur"/>
                <feMerge>
                    <feMergeNode in="blur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
        </defs>
        
        <!-- Background circle -->
        <circle cx="32" cy="32" r="30" fill="#0f172a"/>
        <circle cx="32" cy="32" r="30" fill="url(#domeGradient)" opacity="0.1"/>
        
        <!-- Masjid base -->
        <rect x="16" y="40" width="32" height="12" rx="2" fill="url(#domeGradient)"/>
        
        <!-- Main dome -->
        <ellipse cx="32" cy="40" rx="14" ry="12" fill="url(#domeGradient)"/>
        
        <!-- Dome highlight -->
        <ellipse cx="30" cy="36" rx="6" ry="4" fill="#6ee7b7" opacity="0.3"/>
        
        <!-- Minaret left -->
        <rect x="12" y="32" width="5" height="20" rx="1" fill="url(#domeGradient)"/>
        <circle cx="14.5" cy="32" r="3" fill="url(#domeGradient)"/>
        <rect x="13.5" y="26" width="2" height="6" fill="url(#domeGradient)"/>
        
        <!-- Minaret right -->
        <rect x="47" y="32" width="5" height="20" rx="1" fill="url(#domeGradient)"/>
        <circle cx="49.5" cy="32" r="3" fill="url(#domeGradient)"/>
        <rect x="48.5" y="26" width="2" height="6" fill="url(#domeGradient)"/>
        
        <!-- Door -->
        <rect x="28" y="44" width="8" height="8" rx="4" ry="4" fill="#0f172a"/>
        
        <!-- Crescent moon with animation -->
        <g filter="url(#glow)">
            <circle cx="46" cy="14" r="8" fill="url(#moonGradient)">
                <animate v-if="animated !== false" attributeName="opacity" values="0.9;1;0.9" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="49" cy="12" r="6.5" fill="#0f172a"/>
        </g>
        
        <!-- Stars with twinkle animation -->
        <g fill="#fcd34d" filter="url(#starGlow)">
            <circle cx="20" cy="16" r="1.2">
                <template v-if="animated !== false">
                    <animate attributeName="opacity" values="0.4;1;0.4" dur="1.5s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="1;1.4;1" dur="1.5s" repeatCount="indefinite"/>
                </template>
            </circle>
            <circle cx="38" cy="10" r="1">
                <template v-if="animated !== false">
                    <animate attributeName="opacity" values="1;0.4;1" dur="2s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="0.8;1.2;0.8" dur="2s" repeatCount="indefinite"/>
                </template>
            </circle>
            <circle cx="28" cy="18" r="0.8">
                <template v-if="animated !== false">
                    <animate attributeName="opacity" values="0.6;1;0.6" dur="1.8s" repeatCount="indefinite"/>
                    <animate attributeName="r" values="0.6;1;0.6" dur="1.8s" repeatCount="indefinite"/>
                </template>
            </circle>
            <circle cx="12" cy="24" r="0.7">
                <animate v-if="animated !== false" attributeName="opacity" values="1;0.5;1" dur="2.2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="54" cy="22" r="0.9">
                <animate v-if="animated !== false" attributeName="opacity" values="0.5;1;0.5" dur="1.7s" repeatCount="indefinite"/>
            </circle>
        </g>
    </svg>
</template>
