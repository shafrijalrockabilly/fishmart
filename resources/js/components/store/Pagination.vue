<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-1 my-6">
        <template v-for="(link, index) in links" :key="index">
            <div
                v-if="!link.url"
                v-html="link.label"
                class="px-3.5 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed"
            />
            <Link
                v-else
                :href="link.url"
                v-html="link.label"
                :class="[
                    'px-3.5 py-2 text-sm font-medium border rounded-lg transition',
                    link.active
                        ? 'bg-orange-500 border-orange-500 text-white'
                        : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50',
                ]"
            />
        </template>
    </div>
</template>
