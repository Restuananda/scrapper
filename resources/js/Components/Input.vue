<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    type: { type: String, default: 'text' },
    label: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    error: { type: String, default: '' },
    hint: { type: String, default: '' },
    icon: { type: String, default: '' }, // heroicon name
    disabled: { type: Boolean, default: false },
    required: { type: Boolean, default: false },
    autofocus: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null);
const showPassword = ref(false);

const inputType = computed(() => {
    if (props.type === 'password') {
        return showPassword.value ? 'text' : 'password';
    }
    return props.type;
});

const inputClasses = computed(() => [
    props.icon ? 'input-with-icon' : 'input',
    props.error && 'input-error',
]);

const focus = () => inputRef.value?.focus();

defineExpose({ focus });
</script>

<template>
    <div class="w-full">
        <!-- Label -->
        <label v-if="label" class="label">
            {{ label }}
            <span v-if="required" class="text-danger-400 ml-1">*</span>
        </label>

        <!-- Input wrapper -->
        <div class="relative">
            <!-- Icon -->
            <div v-if="icon" class="input-group-icon">
                <component :is="icon" class="w-5 h-5" />
            </div>

            <!-- Input -->
            <input
                ref="inputRef"
                :type="inputType"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :required="required"
                :autofocus="autofocus"
                :class="inputClasses"
                @input="emit('update:modelValue', $event.target.value)"
            />

            <!-- Password toggle -->
            <button
                v-if="type === 'password'"
                type="button"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-surface-500 hover:text-surface-300 transition-colors"
                @click="showPassword = !showPassword"
            >
                <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>

        <!-- Error message -->
        <p v-if="error" class="form-error">{{ error }}</p>

        <!-- Hint -->
        <p v-else-if="hint" class="text-sm text-surface-500 mt-1.5">{{ hint }}</p>
    </div>
</template>
