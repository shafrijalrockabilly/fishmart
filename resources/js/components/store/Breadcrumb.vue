<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    items: {
        type: Array,
        required: true, // [{ label: 'Beranda', url: '/' }, { label: 'Katalog' }]
    },
});
</script>

<template>
    <nav class="flex py-3 text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li v-for="(item, index) in items" :key="index" class="inline-flex items-center">
                <div v-if="index > 0" class="mr-2 text-gray-400">/</div>
                <Link
                    v-if="item.url"
                    :href="item.url"
                    class="hover:text-orange-600 transition"
                >
                    {{ item.label }}
                </Link>
                <span v-else class="text-gray-800 font-medium">{{ item.label }}</span>
            </li>
        </ol>
    </nav>
</template>
