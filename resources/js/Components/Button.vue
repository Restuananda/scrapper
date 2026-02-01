<script setup>
import { computed } from 'vue';
import LoadingSpinner from './LoadingSpinner.vue';

const props = defineProps({
    type: { type: String, default: 'button' },
    variant: { type: String, default: 'primary' }, // primary, secondary, accent, ghost, danger
    size: { type: String, default: 'md' }, // sm, md, lg
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    icon: { type: Boolean, default: false },
    block: { type: Boolean, default: false },
});

const classes = computed(() => {
    const variants = {
        primary: 'btn-primary',
        secondary: 'btn-secondary',
        accent: 'btn-accent',
        ghost: 'btn-ghost',
        danger: 'btn-danger',
    };

    const sizes = {
        sm: 'btn-sm',
        md: '',
        lg: 'btn-lg',
    };

    return [
        'btn',
        variants[props.variant],
        sizes[props.size],
        props.icon && 'btn-icon',
        props.block && 'w-full',
    ].filter(Boolean).join(' ');
});
</script>

<template>
    <button
        :type="type"
        :class="classes"
        :disabled="disabled || loading"
    >
        <LoadingSpinner v-if="loading" class="w-4 h-4" />
        <slot v-else />
    </button>
</template>
