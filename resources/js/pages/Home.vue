<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import StoreLayout from '@/layouts/StoreLayout.vue';
import ProductCard from '@/components/store/ProductCard.vue';
import Countdown from '@/components/store/Countdown.vue';
import Button from '@/components/store/Button.vue';
import Badge from '@/components/store/Badge.vue';
import { useCurrency } from '@/composables/useCurrency';

defineProps({
    banners: Array,
    categories: Array,
    flashSale: Object,
    topProducts: Array,
    latestProducts: Array,
});

const activeCategoryTab = ref('SEMUA');
</script>

<template>
    <StoreLayout>
        <div class="space-y-12 pb-16">
            <!-- H-01: Hero Banner (Matching Figma) -->
            <section v-if="banners?.length" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="relative rounded-2xl overflow-hidden bg-[#0B132B] text-white shadow-xl">
                    <div class="absolute inset-0 opacity-40 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1600&q=80')"></div>
                    <div class="relative z-10 max-w-2xl px-8 py-20 sm:py-28 sm:px-12">
                        <h1 class="text-3xl sm:text-5xl font-black tracking-tight mb-4 leading-tight">
                            {{ banners[0].title }}
                        </h1>
                        <p class="text-sm sm:text-base text-gray-200 mb-8 leading-relaxed font-normal">
                            {{ banners[0].subtitle }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <Link :href="banners[0].cta_url || '/produk'">
                                <Button variant="primary" size="lg" class="shadow-lg">
                                    {{ banners[0].cta_label || 'BELANJA SEKARANG' }} →
                                </Button>
                            </Link>
                            <Link href="/produk">
                                <Button variant="outline" size="lg" class="bg-white/10 text-white border-white/30 hover:bg-white/20">
                                    🎯 KATALOG FLASH SALE
                                </Button>
                            </Link>
                        </div>

                        <!-- Feature badges under CTA -->
                        <div class="flex flex-wrap items-center gap-6 mt-10 pt-6 border-t border-white/10 text-xs font-semibold text-gray-300">
                            <div class="flex items-center gap-2">🛡️ Weatherproof Gear</div>
                            <div class="flex items-center gap-2">🌊 Saltwater Tested</div>
                            <div class="flex items-center gap-2">🚚 Gratis Ongkir Se-Jawa</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- H-02: Cari Berdasarkan Kategori Grid (12 items matching Figma) -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-black text-gray-950 mb-1">Cari Berdasarkan Kategori</h2>
                    <p class="text-xs text-gray-500">Temukan piranti pancing & apparel teknis sesuai kebutuhan memancing Anda</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="`/produk?category=${cat.slug}`"
                        class="bg-white p-5 rounded-2xl border border-gray-200 text-center hover:border-orange-500 hover:shadow-md transition flex flex-col items-center justify-center group"
                    >
                        <div class="w-14 h-14 bg-gray-50 text-orange-600 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-orange-500 group-hover:text-white transition text-2xl shadow-inner">
                            🎣
                        </div>
                        <span class="text-xs font-bold text-gray-800 line-clamp-1">{{ cat.name }}</span>
                    </Link>
                </div>
            </section>

            <!-- H-03: Flash Sale Section (Matching Figma Dark Navy Block) -->
            <section v-if="flashSale && flashSale.items?.length" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-[#0B132B] rounded-2xl p-6 sm:p-8 text-white shadow-xl">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-2xl font-black text-orange-400 uppercase tracking-wide">⚡ FLASH SALE</h2>
                            <Countdown :endTime="flashSale.ends_at" />
                        </div>
                        <Link href="/produk" class="text-xs font-semibold text-gray-300 hover:text-white">
                            Lihat semua &gt;
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Voucher Card in Flash Sale -->
                        <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl p-5 text-white flex flex-col justify-between shadow-lg">
                            <div>
                                <span class="px-2.5 py-1 bg-black/20 text-[10px] font-bold rounded-full mb-3 inline-block">VOUCHER ANGLER</span>
                                <h3 class="text-base font-black mb-1">SPESIAL HARI INI</h3>
                                <p class="text-xl font-black mb-3">BELANJA 250RB</p>
                                <p class="text-xs text-orange-100 leading-relaxed">POTONGAN LANGSUNG <strong class="text-white">HEMAT 10RB</strong></p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-white/20 text-[10px] font-semibold text-orange-100 flex items-center justify-between">
                                <span>Berlaku s/d 23:59 WIB</span>
                                <span class="bg-white text-orange-600 px-2 py-0.5 rounded font-bold">KLAIM OTOMATIS</span>
                            </div>
                        </div>

                        <!-- Product Flash Sale Cards -->
                        <div v-for="item in flashSale.items.slice(0, 4)" :key="item.id" class="bg-white rounded-xl text-gray-900 p-4 flex flex-col shadow justify-between">
                            <div>
                                <Link :href="`/produk/${item.product.slug}`" class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-3 relative block">
                                    <img
                                        :src="item.product.images?.[0]?.path ? `/storage/${item.product.images[0].path}` : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=500&q=80'"
                                        :alt="item.product.name"
                                        class="object-cover w-full h-full"
                                    />
                                    <div class="absolute top-2 left-2">
                                        <Badge variant="red">-{{ Math.round((1 - item.sale_price / item.product.price) * 100) }}%</Badge>
                                    </div>
                                </Link>
                                <Link :href="`/produk/${item.product.slug}`" class="font-semibold text-xs line-clamp-2 mb-2 hover:text-orange-600">
                                    {{ item.product.name }}
                                </Link>
                            </div>
                            <div class="mt-auto">
                                <div class="flex items-baseline gap-2 mb-2">
                                    <span class="text-sm font-black text-orange-600">{{ useCurrency(item.sale_price) }}</span>
                                    <span class="text-[10px] text-gray-400 line-through">{{ useCurrency(item.product.price) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-gray-500 font-semibold">
                                    <span class="text-orange-600">Sisa {{ item.quota - item.sold }} 💧</span>
                                    <span class="text-gray-400">Cepat habis</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- H-04: Produk Rekomendasi Terlaris with Tabs -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                    <h2 class="text-2xl font-black text-gray-950">Produk Rekomendasi Terlaris</h2>
                    <div class="flex items-center space-x-2 bg-gray-200 p-1 rounded-xl text-xs font-bold">
                        <button
                            @click="activeCategoryTab = 'SEMUA'"
                            :class="['px-4 py-1.5 rounded-lg transition', activeCategoryTab === 'SEMUA' ? 'bg-[#0B132B] text-white shadow' : 'text-gray-600 hover:text-gray-900']"
                        >SEMUA</button>
                        <button
                            @click="activeCategoryTab = 'JORAN'"
                            :class="['px-4 py-1.5 rounded-lg transition', activeCategoryTab === 'JORAN' ? 'bg-[#0B132B] text-white shadow' : 'text-gray-600 hover:text-gray-900']"
                        >JORAN</button>
                        <button
                            @click="activeCategoryTab = 'REEL'"
                            :class="['px-4 py-1.5 rounded-lg transition', activeCategoryTab === 'REEL' ? 'bg-[#0B132B] text-white shadow' : 'text-gray-600 hover:text-gray-900']"
                        >REEL</button>
                        <button
                            @click="activeCategoryTab = 'LURE'"
                            :class="['px-4 py-1.5 rounded-lg transition', activeCategoryTab === 'LURE' ? 'bg-[#0B132B] text-white shadow' : 'text-gray-600 hover:text-gray-900']"
                        >LURE</button>
                        <button
                            @click="activeCategoryTab = 'SENAR'"
                            :class="['px-4 py-1.5 rounded-lg transition', activeCategoryTab === 'SENAR' ? 'bg-[#0B132B] text-white shadow' : 'text-gray-600 hover:text-gray-900']"
                        >SENAR</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard v-for="product in topProducts" :key="product.id" :product="product" />
                </div>
            </section>

            <!-- Flash Strike Weekend Festival Banner (Matching Figma) -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-[#0B132B] rounded-2xl p-8 sm:p-10 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-8 border border-slate-800">
                    <div>
                        <span class="text-orange-400 text-xs font-bold uppercase tracking-wider mb-2 block">PROMO EKSKLUSIF AKHIR PEKAN</span>
                        <h2 class="text-2xl sm:text-3xl font-black mb-3">Flash Strike Weekend Festival</h2>
                        <p class="text-sm text-gray-300 max-w-xl leading-relaxed">
                            Dapatkan diskon eksklusif akhir pekan hingga 50% untuk joran, reel, dan aksesoris memancing pilihan.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-6 bg-slate-900/80 p-6 rounded-2xl border border-slate-800 w-full md:w-auto justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block mb-1">BERAKHIR DALAM</span>
                            <Countdown endTime="2026-10-05T23:59:59" />
                        </div>
                        <Button variant="primary" size="md" class="w-full sm:w-auto">
                            KLAIM VOUCHER SEKARANG →
                        </Button>
                    </div>
                </div>
            </section>

            <!-- H-05: Produk Terbaru Masuk Gudang (Matching Figma) -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">RILIS TERBARU 2026</span>
                        <h2 class="text-2xl font-black text-gray-950">Produk Terbaru Masuk Gudang</h2>
                    </div>
                    <Link href="/produk?sort=latest" class="text-xs font-bold text-orange-600 hover:text-orange-700">
                        LIHAT SEMUA PRODUK TERBARU &gt;
                    </Link>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard v-for="product in latestProducts" :key="product.id" :product="product" />
                </div>
            </section>

            <!-- H-06: Keunggulan Toko (4 feature boxes matching Figma) -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center font-bold flex-shrink-0 text-xl">🎣</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-950 mb-1">Spesialis Alat Pancing</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Kurasi produk dan spesifikasi akurat oleh angler berpengalaman.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center font-bold flex-shrink-0 text-xl">📦</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-950 mb-1">Packing Ekstrak Aman</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Joran dikirim dalam selongsong pipa PVC tebal anti-patah standar ekspedisi.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center font-bold flex-shrink-0 text-xl">💳</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-950 mb-1">Checkout Cepat</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Terintegrasi dengan payment gateway praktis: QRIS, VA Bank & E-Wallet.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center font-bold flex-shrink-0 text-xl">🎧</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-950 mb-1">Layanan Pelanggan</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Konsultasi pemilihan joran, reel, dan piranti laut kapan saja via WhatsApp.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </StoreLayout>
</template>
