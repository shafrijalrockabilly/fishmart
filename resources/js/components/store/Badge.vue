<script setup>
defineProps({
    variant: {
        type: String,
        default: 'orange', // orange, green, gray, red
    },
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold',
            {
                'bg-orange-100 text-orange-800': variant === 'orange',
                'bg-green-100 text-green-800': variant === 'green',
                'bg-gray-100 text-gray-800': variant === 'gray',
                'bg-red-100 text-red-800': variant === 'red',
            },
        ]"
    >
        <slot />
    </span>
</template>
