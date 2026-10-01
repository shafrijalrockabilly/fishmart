<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import StoreLayout from '@/layouts/StoreLayout.vue';
import Breadcrumb from '@/components/store/Breadcrumb.vue';
import Badge from '@/components/store/Badge.vue';
import Button from '@/components/store/Button.vue';
import { useCurrency } from '@/composables/useCurrency';

const props = defineProps({
    cart: Object,
    addresses: Array,
    defaultAddress: Object,
});

const selectedPaymentMethod = ref('va');
const voucherCode = ref('STRIKEHEMAT');
const appliedDiscount = ref(50000);

const updateQuantity = (item, newQty) => {
    if (newQty < 1) return;
    router.patch(`/keranjang/${item.id}`, { quantity: newQty }, { preserveScroll: true });
};

const toggleSelection = (item) => {
    router.patch(`/keranjang/${item.id}`, { is_selected: !item.is_selected }, { preserveScroll: true });
};

const removeItem = (item) => {
    router.delete(`/keranjang/${item.id}`, { preserveScroll: true });
};

const subtotalSelected = computed(() => {
    if (!props.cart?.items) return 0;
    return props.cart.items
        .filter(item => item.is_selected)
        .reduce((sum, item) => {
            const price = item.variant ? (item.variant.sale_price ?? item.variant.price) : (item.product.sale_price ?? item.product.price);
            return sum + (price * item.quantity);
        }, 0);
});

const grandTotal = computed(() => {
    const sub = subtotalSelected.value;
    if (sub === 0) return 0;
    return Math.max(0, sub - appliedDiscount.value);
});

const checkoutForm = useForm({});
const processCheckout = () => {
    checkoutForm.post('/checkout');
};
</script>

<template>
    <StoreLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Breadcrumb :items="[{ label: 'Beranda', url: '/' }, { label: 'Keranjang Belanja', url: '/keranjang' }, { label: 'Checkout & Pembayaran' }]" />

            <!-- Stepper Header (Matching Figma) -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm my-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm font-semibold">
                    <div class="flex items-center space-x-3 text-orange-600 bg-orange-50/50 p-3 rounded-xl border border-orange-100">
                        <div class="w-8 h-8 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold text-xs">✓</div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-orange-500">LANGKAH 1</span>
                            <span>Keranjang Pilihan</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 text-gray-800 bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <div class="w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center font-bold text-xs">2</div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-gray-400">SEDANG BERLANGSUNG</span>
                            <span>Pengiriman & Pembayaran</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 text-gray-400 bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">3</div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-gray-400">LANGKAH AKHIR</span>
                            <span>Pesanan Selesai</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Grid: Cart Items (Left) & Checkout Summary (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Cart Items & Protection -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Free shipping progress bar -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-700">🚚 Belanja Rp 50.000 lagi untuk menikmati Bebas Ongkir Cargo seluruh Indonesia!</span>
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full">85% Tercapai</span>
                    </div>

                    <!-- Cart items wrapper -->
                    <div v-if="cart?.items?.length" class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b pb-4">
                            <label class="flex items-center space-x-2 text-sm font-bold text-gray-900 cursor-pointer">
                                <input type="checkbox" checked class="rounded text-orange-600 focus:ring-orange-500 w-4 h-4" />
                                <span>Pilih Semua ({{ cart.items.length }} item terpilih)</span>
                            </label>
                            <button class="text-xs font-semibold text-red-600 hover:underline">Hapus Semua</button>
                        </div>

                        <!-- Item Card -->
                        <div v-for="item in cart.items" :key="item.id" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-gray-100 last:border-none last:pb-0">
                            <div class="flex items-start space-x-4">
                                <input
                                    type="checkbox"
                                    :checked="item.is_selected"
                                    @change="toggleSelection(item)"
                                    class="mt-3 rounded text-orange-600 focus:ring-orange-500 w-4 h-4"
                                />
                                <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 border">
                                    <img
                                        :src="item.product.images?.[0]?.path ? `/storage/${item.product.images[0].path}` : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=300&q=80'"
                                        class="object-cover w-full h-full"
                                    />
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="px-2 py-0.5 bg-[#0B132B] text-white text-[9px] font-bold rounded">OFFICIAL STORE</span>
                                        <span class="text-[10px] text-gray-500 font-medium">Garansi Resmi 1 Tahun</span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm mb-1 line-clamp-1">{{ item.product.name }}</h4>
                                    <p v-if="item.variant" class="text-xs text-gray-500 mb-2">Varian: {{ item.variant.name }}</p>
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-bold text-gray-900 text-sm">
                                            {{ useCurrency(item.variant ? (item.variant.sale_price ?? item.variant.price) : (item.product.sale_price ?? item.product.price)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-3">
                                <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden bg-white">
                                    <button @click="updateQuantity(item, item.quantity - 1)" class="px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                                    <span class="px-3 py-1 text-xs font-bold text-gray-900">{{ item.quantity }}</span>
                                    <button @click="updateQuantity(item, item.quantity + 1)" class="px-3 py-1 text-gray-600 hover:bg-gray-100">+</button>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-gray-400 block">Subtotal</span>
                                    <span class="font-bold text-sm text-orange-600">
                                        {{ useCurrency((item.variant ? (item.variant.sale_price ?? item.variant.price) : (item.product.sale_price ?? item.product.price)) * item.quantity) }}
                                    </span>
                                </div>
                                <button @click="removeItem(item)" class="text-xs text-red-500 hover:underline">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div v-else class="bg-white p-12 rounded-2xl border border-gray-200 text-center shadow-sm">
                        <div class="text-4xl mb-3">🛒</div>
                        <h3 class="font-bold text-gray-900 mb-1">Keranjang Belanja Anda Kosong</h3>
                        <p class="text-xs text-gray-500 mb-6">Yuk, jelajahi katalog perlengkapan pancing kami!</p>
                        <Link href="/produk">
                            <Button variant="primary" size="md">Mulai Belanja</Button>
                        </Link>
                    </div>

                    <!-- FishMart Guard Protection Box (Matching Figma) -->
                    <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-5 flex items-start space-x-4">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold flex-shrink-0">🛡️</div>
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <h4 class="font-bold text-sm text-gray-900">Proteksi Pengiriman Fisik FishMart Guard™</h4>
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 text-[10px] font-bold rounded-full">100% Gratis</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Semua joran carbon, sensitive tip, & reel pancing dipacking otomatis menggunakan <strong class="text-gray-900">Pipa PVC Paralon Tebal & Bubble Wrap 4 Lapis</strong> tanpa biaya tambahan sepeser pun!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right: Ringkasan Checkout & Pembayaran (Matching Figma) -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b pb-4">
                            <div>
                                <h3 class="font-bold text-gray-900">Ringkasan Checkout</h3>
                                <p class="text-[10px] text-gray-500">PROSES CEPAT & AMAN</p>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-100 text-xs font-bold text-gray-800 rounded-lg">FM-88219</span>
                        </div>

                        <!-- Delivery Address -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-gray-700 uppercase">Alamat Pengiriman</span>
                                <button class="text-xs font-semibold text-orange-600 hover:underline">Ubah Alamat</button>
                            </div>
                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-200 text-xs space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-gray-900">{{ defaultAddress?.recipient_name || 'Bambang Prakoso' }}</span>
                                    <span class="px-1.5 py-0.5 bg-gray-200 text-gray-800 text-[9px] font-bold rounded">Rumah</span>
                                </div>
                                <p class="text-gray-600">{{ defaultAddress?.phone || '+62 812-3456-7890' }}</p>
                                <p class="text-gray-500">{{ defaultAddress?.detail || 'Jl. Danau Sunter Utara Blok F No. 12, Kel. Sunter Agung, Tanjung Priok, Kota Jakarta Utara' }}</p>
                            </div>
                        </div>

                        <!-- Shipping Option -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Opsi Pengiriman</label>
                            <select class="w-full px-3 py-2.5 text-xs border border-gray-300 rounded-xl bg-white font-medium">
                                <option>J&T Cargo Express - Estimasi Tiba Besok (Bebas Ongkir Rp 0)</option>
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Metode Pembayaran</label>
                            <div class="space-y-2 text-xs">
                                <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer border-orange-500 bg-orange-50/50">
                                    <div class="flex items-center space-x-2">
                                        <input type="radio" name="payment" checked class="text-orange-600 focus:ring-orange-500" />
                                        <span class="font-bold text-gray-900">Virtual Account (BCA, Mandiri, BRI)</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-orange-600 bg-orange-100 px-2 py-0.5 rounded">Terpilih</span>
                                </label>
                                <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer border-gray-200 hover:bg-gray-50">
                                    <div class="flex items-center space-x-2">
                                        <input type="radio" name="payment" class="text-orange-600 focus:ring-orange-500" />
                                        <span class="font-medium text-gray-800">QRIS / GoPay / OVO / ShopeePay</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Voucher Input -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Voucher Diskon & Promo</label>
                            <div class="flex space-x-2">
                                <input v-model="voucherCode" type="text" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-xl uppercase font-bold" />
                                <Button variant="secondary" size="sm">Terapkan</Button>
                            </div>
                            <div class="mt-2 flex items-center justify-between bg-green-50 p-2.5 rounded-xl border border-green-100 text-xs">
                                <span class="font-bold text-green-800">✓ STRIKEHEMAT (-Rp 50.000)</span>
                                <button class="text-gray-400 hover:text-red-600">×</button>
                            </div>
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="space-y-2 text-xs pt-4 border-t border-gray-200">
                            <div class="flex justify-between text-gray-600">
                                <span>Total Harga Barang ({{ cart?.items?.length || 0 }} item)</span>
                                <span class="font-semibold text-gray-900">{{ useCurrency(subtotalSelected) }}</span>
                            </div>
                            <div class="flex justify-between text-green-600 font-semibold">
                                <span>Diskon Kupon (STRIKEHEMAT)</span>
                                <span>-{{ useCurrency(appliedDiscount) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Biaya Pengiriman Cargo</span>
                                <span class="font-bold text-green-600">GRATIS <span class="line-through text-gray-400 font-normal">Rp 45.000</span></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Proteksi Kemasan Pipa PVC & Asuransi</span>
                                <span class="font-bold text-green-600">GRATIS</span>
                            </div>
                        </div>

                        <!-- Grand Total & CTA -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-baseline mb-4">
                                <span class="text-sm font-bold text-gray-900">Total Tagihan</span>
                                <span class="text-2xl font-black text-orange-600">{{ useCurrency(grandTotal) }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400 mb-4">Termasuk PPN 11% & Asuransi Pengiriman</p>

                            <Button
                                variant="primary"
                                size="lg"
                                class="w-full py-4 text-base font-bold shadow-lg"
                                :disabled="!cart?.items?.length"
                                @click="processCheckout"
                            >
                                🔒 Bayar Sekarang • {{ useCurrency(grandTotal) }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>
