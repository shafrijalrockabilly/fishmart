<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import StoreLayout from '@/layouts/StoreLayout.vue';
import Breadcrumb from '@/components/store/Breadcrumb.vue';
import Rating from '@/components/store/Rating.vue';
import Badge from '@/components/store/Badge.vue';
import Button from '@/components/store/Button.vue';
import ProductCard from '@/components/store/ProductCard.vue';
import { useCurrency } from '@/composables/useCurrency';

const props = defineProps({
    product: Object,
    relatedProducts: Array,
});

// Active image gallery index
const activeImageIndex = ref(0);
const activeImage = computed(() => {
    return props.product.images?.[activeImageIndex.value]?.path
        ? `/storage/${props.product.images[activeImageIndex.value].path}`
        : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80';
});

// Variant selection
const selectedVariantId = ref(props.product.variants?.[0]?.id || null);
const selectedVariant = computed(() => {
    if (!props.product.has_variants || !selectedVariantId.value) return null;
    return props.product.variants.find(v => v.id === selectedVariantId.value);
});

// Active price & stock
const activePrice = computed(() => {
    if (props.product.flash_sale_items?.length) {
        return props.product.flash_sale_items[0].sale_price;
    }
    if (selectedVariant.value) {
        return selectedVariant.value.sale_price ?? selectedVariant.value.price;
    }
    return props.product.sale_price ?? props.product.price;
});

const originalPrice = computed(() => {
    if (selectedVariant.value) return selectedVariant.value.price;
    return props.product.price;
});

const activeStock = computed(() => {
    if (selectedVariant.value) return selectedVariant.value.stock;
    return props.product.stock;
});

// Quantity
const quantity = ref(1);
const increment = () => {
    if (quantity.value < activeStock.value) quantity.value++;
};
const decrement = () => {
    if (quantity.value > 1) quantity.value--;
};

// Active tab ('description' or 'reviews')
const activeTab = ref('description');

// Add to cart form
const form = useForm({
    product_id: props.product.id,
    product_variant_id: null,
    quantity: 1,
});

const addToCart = () => {
    form.product_variant_id = selectedVariantId.value;
    form.quantity = quantity.value;
    form.post('/keranjang', {
        preserveScroll: true,
        onSuccess: () => {
            alert('Produk berhasil ditambahkan ke keranjang!');
        },
    });
};
</script>

<template>
    <StoreLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Breadcrumb :items="[
                { label: 'Beranda', url: '/' },
                { label: 'Katalog', url: '/produk' },
                { label: product.name }
            ]" />

            <!-- Product Main Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mt-6 bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
                <!-- Gallery (D-01) -->
                <div class="space-y-4">
                    <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden border border-gray-100">
                        <img :src="activeImage" :alt="product.name" class="object-cover w-full h-full" />
                    </div>
                    <div v-if="product.images?.length > 1" class="grid grid-cols-5 gap-3">
                        <button
                            v-for="(img, idx) in product.images"
                            :key="img.id"
                            @click="activeImageIndex = idx"
                            :class="[
                                'aspect-square rounded-xl overflow-hidden border-2 transition',
                                activeImageIndex === idx ? 'border-orange-500 ring-2 ring-orange-200' : 'border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            <img :src="`/storage/${img.path}`" class="object-cover w-full h-full" />
                        </button>
                    </div>
                </div>

                <!-- Product Info (D-02, D-03, D-04, D-05, D-06) -->
                <div class="flex flex-col">
                    <div class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-1">
                        {{ product.brand?.name || 'FishMart' }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-950 mb-3">{{ product.name }}</h1>

                    <div class="flex items-center space-x-4 mb-4">
                        <Rating :rating="product.rating_avg" :count="product.rating_count" />
                        <span class="text-gray-300">|</span>
                        <span class="text-xs text-gray-500">Terjual <strong class="text-gray-800">{{ product.sold_count }}</strong></span>
                    </div>

                    <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 mb-6 flex items-baseline gap-3">
                        <span class="text-3xl font-black text-orange-600">{{ useCurrency(activePrice) }}</span>
                        <span v-if="originalPrice > activePrice" class="text-sm text-gray-400 line-through">
                            {{ useCurrency(originalPrice) }}
                        </span>
                        <Badge v-if="product.flash_sale_items?.length" variant="red">Flash Sale</Badge>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        {{ product.short_description }}
                    </p>

                    <!-- Variants (D-03) -->
                    <div v-if="product.has_variants && product.variants?.length" class="mb-6">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Pilih Varian:</label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="variant in product.variants"
                                :key="variant.id"
                                @click="selectedVariantId = variant.id"
                                :class="[
                                    'px-4 py-2 text-sm font-medium rounded-xl border transition',
                                    selectedVariantId === variant.id
                                        ? 'bg-orange-500 border-orange-500 text-white shadow-sm'
                                        : 'bg-white border-gray-300 text-gray-700 hover:border-gray-400'
                                ]"
                            >
                                {{ variant.name }}
                            </button>
                        </div>
                    </div>

                    <!-- Stock & Quantity (D-04) -->
                    <div class="flex items-center space-x-6 mb-8">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase mb-2">Stok:</span>
                            <span :class="['text-sm font-bold', activeStock > 0 ? 'text-green-600' : 'text-red-600']">
                                {{ activeStock > 0 ? `${activeStock} tersedia` : 'Habis' }}
                            </span>
                        </div>

                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase mb-2">Jumlah:</span>
                            <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden bg-white">
                                <button @click="decrement" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 transition">-</button>
                                <span class="px-4 py-1.5 text-sm font-semibold text-gray-900">{{ quantity }}</span>
                                <button @click="increment" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 transition">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Actions (D-05, D-06) -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                        <Button
                            variant="primary"
                            size="lg"
                            class="flex-1"
                            :disabled="activeStock <= 0"
                            @click="addToCart"
                        >
                            🛒 Tambah ke Keranjang
                        </Button>
                        <Button variant="secondary" size="lg" class="flex-1" :disabled="activeStock <= 0">
                            ⚡ Beli Sekarang
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Tabs: Description & Specs / Reviews (D-07, D-08) -->
            <div class="mt-12 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex border-b border-gray-200">
                    <button
                        @click="activeTab = 'description'"
                        :class="[
                            'px-6 py-4 text-sm font-bold transition border-b-2',
                            activeTab === 'description' ? 'border-orange-500 text-orange-600 bg-orange-50/50' : 'border-transparent text-gray-500 hover:text-gray-900'
                        ]"
                    >
                        Deskripsi & Spesifikasi
                    </button>
                    <button
                        @click="activeTab = 'reviews'"
                        :class="[
                            'px-6 py-4 text-sm font-bold transition border-b-2',
                            activeTab === 'reviews' ? 'border-orange-500 text-orange-600 bg-orange-50/50' : 'border-transparent text-gray-500 hover:text-gray-900'
                        ]"
                    >
                        Ulasan Pelanggan ({{ product.reviews?.length || 0 }})
                    </button>
                </div>

                <div class="p-6 sm:p-8">
                    <div v-if="activeTab === 'description'" class="space-y-6">
                        <div class="text-gray-700 leading-relaxed space-y-4" v-html="product.description"></div>

                        <div v-if="product.specs?.length" class="mt-8">
                            <h3 class="font-bold text-gray-900 mb-4">Spesifikasi Teknis</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-2xl">
                                <div v-for="spec in product.specs" :key="spec.id" class="flex justify-between p-3 bg-gray-50 rounded-xl text-sm">
                                    <span class="text-gray-500 font-medium">{{ spec.label }}</span>
                                    <span class="text-gray-900 font-semibold">{{ spec.value }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="space-y-6">
                        <div v-if="product.reviews?.length" class="space-y-4">
                            <div v-for="review in product.reviews" :key="review.id" class="p-4 border-b border-gray-100 last:border-none">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-gray-900">{{ review.user?.name || 'Anonim' }}</span>
                                    <Rating :rating="review.rating" />
                                </div>
                                <p class="text-sm text-gray-600">{{ review.comment }}</p>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 text-gray-500 text-sm">
                            Belum ada ulasan untuk produk ini.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products (D-09) -->
            <div v-if="relatedProducts?.length" class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard v-for="item in relatedProducts" :key="item.id" :product="item" />
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
