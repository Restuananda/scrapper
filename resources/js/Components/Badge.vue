<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: { type: String, default: 'primary' }, // primary, success, warning, danger, accent
    size: { type: String, default: 'md' }, // sm, md
    dot: { type: Boolean, default: false },
});

const classes = computed(() => {
    const variants = {
        primary: 'badge-primary',
        success: 'badge-success',
        warning: 'badge-warning',
        danger: 'badge-danger',
        accent: 'badge-accent',
    };

    return [
        'badge',
        variants[props.variant],
        props.size === 'sm' && 'text-[10px] px-2 py-0.5',
    ].filter(Boolean).join(' ');
});
</script>

<template>
    <span :class="classes">
        <span v-if="dot" class="w-1.5 h-1.5 rounded-full bg-current" />
        <slot />
    </span>
</template>
