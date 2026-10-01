<script setup>
import { reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import StoreLayout from '@/layouts/StoreLayout.vue';
import ProductCard from '@/components/store/ProductCard.vue';
import Breadcrumb from '@/components/store/Breadcrumb.vue';
import Pagination from '@/components/store/Pagination.vue';
import Button from '@/components/store/Button.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    brands: Array,
    filters: Object,
});

const form = reactive({
    q: props.filters.q || '',
    category: props.filters.category || '',
    brand: props.filters.brand || '',
    min_price: props.filters.min_price || '',
    max_price: props.filters.max_price || '',
    rating: props.filters.rating || '',
    in_stock: props.filters.in_stock ? true : false,
    on_sale: props.filters.on_sale ? true : false,
    sort: props.filters.sort || 'latest',
});

const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

const applyFilters = debounce(() => {
    router.get('/produk', form, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch(form, () => {
    applyFilters();
});

const resetFilters = () => {
    form.q = '';
    form.category = '';
    form.brand = '';
    form.min_price = '';
    form.max_price = '';
    form.rating = '';
    form.in_stock = false;
    form.on_sale = false;
    form.sort = 'latest';
};
</script>

<template>
    <StoreLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Breadcrumb :items="[{ label: 'Beranda', url: '/' }, { label: 'Katalog Produk' }]" />

            <div class="flex flex-col md:flex-row gap-8 mt-6">
                <!-- Sidebar Filter -->
                <aside class="w-full md:w-72 flex-shrink-0 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-6 h-fit">
                    <div class="flex items-center justify-between border-b pb-4">
                        <h3 class="font-bold text-gray-900">Filter Produk</h3>
                        <button @click="resetFilters" class="text-xs font-semibold text-orange-600 hover:underline">
                            Hapus Semua
                        </button>
                    </div>

                    <!-- Search keyword -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Pencarian</label>
                        <input
                            v-model="form.q"
                            type="text"
                            placeholder="Cari produk..."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500"
                        />
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Kategori</label>
                        <select
                            v-model="form.category"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500"
                        >
                            <option value="">Semua Kategori</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.slug">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Brands -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Merek</label>
                        <select
                            v-model="form.brand"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500"
                        >
                            <option value="">Semua Merek</option>
                            <option v-for="brand in brands" :key="brand.id" :value="brand.slug">
                                {{ brand.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Rentang Harga (Rp)</label>
                        <div class="flex items-center space-x-2">
                            <input
                                v-model="form.min_price"
                                type="number"
                                placeholder="Min"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl"
                            />
                            <span class="text-gray-400">-</span>
                            <input
                                v-model="form.max_price"
                                type="number"
                                placeholder="Maks"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl"
                            />
                        </div>
                    </div>

                    <!-- Status Toggles -->
                    <div class="space-y-3 pt-2 border-t">
                        <label class="flex items-center space-x-2 cursor-pointer text-sm text-gray-700">
                            <input v-model="form.in_stock" type="checkbox" class="rounded text-orange-600 focus:ring-orange-500" />
                            <span>Stok Tersedia</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer text-sm text-gray-700">
                            <input v-model="form.on_sale" type="checkbox" class="rounded text-orange-600 focus:ring-orange-500" />
                            <span>Sedang Diskon</span>
                        </label>
                    </div>
                </aside>

                <!-- Product Catalog Main Area -->
                <main class="flex-1">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-4 rounded-2xl border border-gray-200 shadow-sm mb-6 gap-4">
                        <div>
                            <h1 class="text-xl font-black text-gray-950">Katalog Perlengkapan Pancing</h1>
                            <p class="text-xs text-gray-500">Menampilkan {{ products.total }} produk pilihan</p>
                        </div>

                        <!-- Sorting -->
                        <div class="flex items-center space-x-2 w-full sm:w-auto">
                            <span class="text-xs font-semibold text-gray-500 whitespace-nowrap">Urutkan:</span>
                            <select
                                v-model="form.sort"
                                class="px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 w-full sm:w-auto"
                            >
                                <option value="latest">Terbaru</option>
                                <option value="sold">Terlaris</option>
                                <option value="price_asc">Harga Terendah</option>
                                <option value="price_desc">Harga Tertinggi</option>
                                <option value="rating">Rating Tertinggi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div v-if="products.data.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                    </div>

                    <!-- Empty State -->
                    <div v-else class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
                        <div class="text-5xl mb-4">🎣</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Coba ubah kata kunci pencarian atau atur ulang filter Anda.</p>
                        <Button variant="primary" size="md" @click="resetFilters">Reset Semua Filter</Button>
                    </div>

                    <!-- Pagination -->
                    <Pagination :links="products.links" class="mt-8" />
                </main>
            </div>
        </div>
    </StoreLayout>
</template>
