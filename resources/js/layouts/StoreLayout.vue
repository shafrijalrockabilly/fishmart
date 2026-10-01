<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();
const searchQuery = ref('');

const search = () => {
    if (searchQuery.value.trim()) {
        router.get('/produk', { q: searchQuery.value }, { preserveState: true });
    }
};

const user = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50 text-gray-900 font-sans">
        <!-- Navbar -->
        <header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-2 flex-shrink-0">
                    <div class="w-10 h-10 bg-[#0B132B] rounded-xl flex items-center justify-center text-orange-500 font-black text-xl">
                        FM
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-tight text-[#0B132B]">FISHMART</span>
                        <span class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest">Pusat Alat Pancing Terpercaya</span>
                    </div>
                </Link>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-4 hidden md:flex">
                    <form @submit.prevent="search" class="relative w-full flex">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari joran carbon, reel shimano, umpan casting, senar PE..."
                            class="w-full pl-4 pr-24 py-2.5 bg-gray-50 border border-gray-300 rounded-l-xl focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition text-sm"
                        />
                        <button type="submit" class="px-6 bg-orange-500 hover:bg-orange-600 text-white rounded-r-xl transition flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-6">
                    <Link href="#" class="text-xs font-semibold text-gray-700 hover:text-orange-600 hidden lg:block">Bantuan</Link>
                    
                    <!-- Cart Icon with Badge -->
                    <Link href="/keranjang" class="relative p-2 text-gray-700 hover:text-orange-500 transition rounded-xl hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                            3
                        </span>
                    </Link>

                    <!-- Account Menu -->
                    <div class="flex items-center space-x-3">
                        <template v-if="user">
                            <Link v-if="user.role === 'admin'" href="/admin" class="px-3 py-1.5 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition shadow-sm">
                                Dashboard Admin
                            </Link>
                            <div class="flex items-center space-x-2 bg-gray-100 px-3 py-1.5 rounded-xl border border-gray-200">
                                <span class="text-xs font-bold text-gray-800">👤 {{ user.name }}</span>
                                <Link href="/logout" method="post" as="button" class="text-xs text-red-600 hover:underline font-semibold ml-2">
                                    Keluar
                                </Link>
                            </div>
                        </template>
                        <template v-else>
                            <Link href="/login" class="px-5 py-2 text-xs font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition shadow-sm uppercase tracking-wider">
                                Masuk
                            </Link>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Categories Nav -->
            <nav class="bg-[#0B132B] text-white hidden md:block">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-12 text-xs font-bold tracking-wider uppercase">
                    <Link href="/produk" class="hover:text-orange-400 transition">Semua Kategori</Link>
                    <Link href="/produk?category=joran-pancing" class="hover:text-orange-400 transition">Joran (Fishing Rod)</Link>
                    <Link href="/produk?category=reel-pancing" class="hover:text-orange-400 transition">Reel Pancing</Link>
                    <Link href="/produk?category=umpan-buatan" class="hover:text-orange-400 transition">Umpan & Lure</Link>
                    <Link href="/produk?category=senar-pe" class="hover:text-orange-400 transition">Senar PE & Monofilament</Link>
                    <Link href="/produk?category=aksesoris-box" class="hover:text-orange-400 transition">Aksesoris & Box</Link>
                    <Link href="/produk?sort=latest" class="hover:text-orange-400 transition">Promo Hari Ini</Link>
                    <Link href="/produk" class="text-orange-400 hover:text-orange-300 transition flex items-center gap-1">⚡ Flash Sale</Link>
                    <Link href="/produk" class="hover:text-orange-400 transition flex items-center gap-1">🎯 Tactical Angling</Link>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-[#0B132B] text-gray-300 pt-16 pb-12 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center text-white font-black text-lg">
                            FM
                        </div>
                        <span class="text-lg font-black text-white">FISHMART</span>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed mb-4">
                        Platform e-commerce spesialis perlengkapan memancing terlengkap di Indonesia. 100% Original & Bergaransi Resmi.
                    </p>
                    <div class="text-xs text-gray-400 space-y-1">
                        <p>📍 Kantor Operasional: Jakarta Marunda & Surabaya Tanjung Perak</p>
                        <p>📞 Call Center WA: 0812-FISH-MART</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-white font-bold mb-4 text-xs uppercase tracking-wider">Kategori Populer</h3>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><Link href="/produk?category=joran-pancing" class="hover:text-orange-400 transition">Joran Spinning</Link></li>
                        <li><Link href="/produk?category=joran-pancing" class="hover:text-orange-400 transition">Joran Baitcasting</Link></li>
                        <li><Link href="/produk?category=reel-pancing" class="hover:text-orange-400 transition">Reel Spinning</Link></li>
                        <li><Link href="/produk?category=reel-pancing" class="hover:text-orange-400 transition">Reel Overhead</Link></li>
                        <li><Link href="/produk?category=umpan-buatan" class="hover:text-orange-400 transition">Umpan Minnow & Metal Jig</Link></li>
                        <li><Link href="/produk?category=senar-pe" class="hover:text-orange-400 transition">Senar Braid PE 8x / 12x</Link></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold mb-4 text-xs uppercase tracking-wider">Layanan Pelanggan</h3>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><a href="#" class="hover:text-orange-400 transition">Cara Pemesanan</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Pengiriman & Ongkir</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Garansi & Retur Produk</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Konfirmasi Pembayaran</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Hubungi CS Operasional</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold mb-4 text-xs uppercase tracking-wider">Pembayaran & Ekspedisi</h3>
                    <p class="text-xs text-gray-400 mb-3">Metode Pembayaran Resmi</p>
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        <span class="px-2 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">BCA</span>
                        <span class="px-2 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">MANDIRI</span>
                        <span class="px-2 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">BRI</span>
                        <span class="px-2 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">QRIS</span>
                        <span class="px-2 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">GOPAY</span>
                        <span class="px-2 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">SHOPEEPAY</span>
                    </div>
                    <p class="text-xs text-gray-400 mb-2">Mitra Logistik Terpercaya</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">JNE TRUCKING</span>
                        <span class="px-2.5 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">J&T CARGO</span>
                        <span class="px-2.5 py-1 bg-slate-800 text-[10px] font-bold rounded text-white">SICEPAT</span>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                <p>&copy; {{ new Date().getFullYear() }} FishMart Indonesia. All rights reserved. Solusi Memancing Juara.</p>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-orange-400">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-orange-400">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-orange-400">Sertifikasi Sonar</a>
                </div>
            </div>
        </footer>
    </div>
</template>
