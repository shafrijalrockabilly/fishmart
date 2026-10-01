<script setup>
import { Link } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
import Rating from './Rating.vue';
import Badge from './Badge.vue';
import Button from './Button.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const discountPercentage = (price, salePrice) => {
    if (!salePrice || salePrice >= price) return 0;
    return Math.round(((price - salePrice) / price) * 100);
};
</script>

<template>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
        <Link :href="`/produk/${product.slug}`" class="relative block aspect-square bg-gray-100 overflow-hidden">
            <img
                :src="product.images?.[0]?.path ? `/storage/${product.images[0].path}` : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=500&q=80'"
                :alt="product.name"
                loading="lazy"
                class="object-cover w-full h-full group-hover:scale-105 transition duration-300"
            />
            <div v-if="product.sale_price && product.sale_price < product.price" class="absolute top-2 left-2">
                <Badge variant="red">
                    -{{ discountPercentage(product.price, product.sale_price) }}%
                </Badge>
            </div>
        </Link>

        <div class="p-4 flex flex-col flex-grow">
            <div class="text-xs text-gray-500 mb-1">
                {{ product.brand?.name || 'FishMart' }}
            </div>
            <Link :href="`/produk/${product.slug}`" class="font-semibold text-gray-900 line-clamp-2 hover:text-orange-600 transition mb-2">
                {{ product.name }}
            </Link>

            <div class="mt-auto">
                <div class="flex items-center justify-between mb-2">
                    <Rating :rating="product.rating_avg" :count="product.rating_count" />
                    <span class="text-xs text-gray-500">Terjual {{ product.sold_count }}</span>
                </div>

                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-bold text-gray-900">
                        {{ useCurrency(product.sale_price ?? product.price) }}
                    </span>
                    <span v-if="product.sale_price && product.sale_price < product.price" class="text-xs text-gray-400 line-through">
                        {{ useCurrency(product.price) }}
                    </span>
                </div>

                <Link :href="`/produk/${product.slug}`" class="w-full">
                    <Button variant="primary" size="md" class="w-full">
                        Lihat Detail
                    </Button>
                </Link>
            </div>
        </div>
    </div>
</template>
