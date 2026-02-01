<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: { type: String, default: 'default' }, // default, hover, interactive
    padding: { type: String, default: 'md' }, // none, sm, md, lg
    noBorder: { type: Boolean, default: false },
});

const classes = computed(() => {
    const variants = {
        default: 'card',
        hover: 'card-hover',
        interactive: 'card-interactive',
    };

    const paddings = {
        none: '',
        sm: 'p-4',
        md: 'p-6',
        lg: 'p-8',
    };

    return [
        variants[props.variant],
        paddings[props.padding],
        props.noBorder && 'border-0',
    ].filter(Boolean).join(' ');
});
</script>

<template>
    <div :class="classes">
        <slot />
    </div>
</template>
