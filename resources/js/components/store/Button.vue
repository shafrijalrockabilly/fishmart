<script setup>
defineProps({
    variant: {
        type: String,
        default: 'primary', // primary, secondary, outline, danger
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
    type: {
        type: String,
        default: 'button',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="[
            'inline-flex items-center justify-center font-medium transition rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2',
            {
                'bg-orange-500 hover:bg-orange-600 text-white focus:ring-orange-500': variant === 'primary',
                'bg-slate-800 hover:bg-slate-900 text-white focus:ring-slate-800': variant === 'secondary',
                'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 focus:ring-orange-500': variant === 'outline',
                'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500': variant === 'danger',
                'px-2.5 py-1.5 text-xs': size === 'sm',
                'px-4 py-2 text-sm': size === 'md',
                'px-6 py-3 text-base': size === 'lg',
                'opacity-50 cursor-not-allowed': disabled,
            },
        ]"
    >
        <slot />
    </button>
</template>
