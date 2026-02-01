<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    align: { type: String, default: 'right' }, // left, right
    width: { type: String, default: '48' }, // 48, 60, 72
});

const open = ref(false);
const dropdownRef = ref(null);

const widthClass = {
    '48': 'w-48',
    '60': 'w-60',
    '72': 'w-72',
};

const alignClass = {
    left: 'left-0',
    right: 'right-0',
};

const closeOnOutsideClick = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeOnOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener('click', closeOnOutsideClick);
});
</script>

<template>
    <div ref="dropdownRef" class="relative">
        <!-- Trigger -->
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <!-- Dropdown Content -->
        <Transition
            enter-active-class="duration-150 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="duration-100 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="open"
                :class="['dropdown', widthClass[width], alignClass[align]]"
                @click="open = false"
            >
                <slot name="content" />
            </div>
        </Transition>
    </div>
</template>
