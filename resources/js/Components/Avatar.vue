<script setup>
import { computed } from 'vue';

const props = defineProps({
    src: { type: String, default: '' },
    name: { type: String, default: '' },
    size: { type: String, default: 'md' }, // sm, md, lg, xl
    status: { type: String, default: '' }, // online, offline, away
});

const sizes = {
    sm: 'avatar-sm',
    md: 'avatar-md',
    lg: 'avatar-lg',
    xl: 'avatar-xl',
};

const initials = computed(() => {
    if (!props.name) return '?';
    return props.name
        .split(' ')
        .map(n => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
});

const statusColors = {
    online: 'bg-success-500',
    offline: 'bg-surface-500',
    away: 'bg-warning-500',
};
</script>

<template>
    <div class="relative inline-flex">
        <div :class="['avatar', sizes[size]]">
            <img
                v-if="src"
                :src="src"
                :alt="name"
                class="w-full h-full object-cover"
            />
            <span v-else>{{ initials }}</span>
        </div>
        
        <!-- Status indicator -->
        <span
            v-if="status"
            :class="[
                'absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-surface-900',
                statusColors[status]
            ]"
        />
    </div>
</template>
