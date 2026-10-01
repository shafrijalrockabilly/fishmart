<script setup>
defineProps({
    rating: {
        type: [Number, String],
        default: 0,
    },
    count: {
        type: [Number, String],
        default: null,
    },
});
</script>

<template>
    <div class="flex items-center space-x-1 text-amber-500">
        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
        <span class="text-sm font-semibold text-gray-800">{{ Number(rating).toFixed(1) }}</span>
        <span v-if="count !== null" class="text-xs text-gray-500">({{ count }})</span>
    </div>
</template>
