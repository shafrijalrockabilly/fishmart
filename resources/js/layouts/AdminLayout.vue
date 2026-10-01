<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="min-h-screen flex bg-gray-100 font-sans text-gray-900">
        <!-- Full-Height Sidebar (Matching Figma) -->
        <aside class="w-72 bg-[#0B132B] text-white flex flex-col flex-shrink-0 min-h-screen sticky top-0">
            <!-- Brand -->
            <div class="p-6 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center font-black text-xl text-white">
                        FM
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider block">FishMart Indonesia</span>
                        <span class="text-[10px] text-orange-400 font-bold uppercase tracking-widest">Admin Panel</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 px-4 py-6 space-y-1.5 text-sm">
                <div class="px-3 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">NAVIGASI UTAMA</div>
                
                <Link href="/admin" class="flex items-center justify-between px-4 py-3 rounded-xl bg-orange-600 text-white font-semibold transition">
                    <div class="flex items-center space-x-3">
                        <span>📊</span>
                        <span>Dashboard</span>
                    </div>
                </Link>

                <Link href="#" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <span>📦</span>
                        <span>Produk</span>
                    </div>
                </Link>

                <Link href="#" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <span>🗂️</span>
                        <span>Kategori</span>
                    </div>
                </Link>

                <Link href="#" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <span>⚡</span>
                        <span>Flash Sale</span>
                    </div>
                    <span class="px-2 py-0.5 bg-orange-500/20 text-orange-400 text-[10px] font-bold rounded">PROMO</span>
                </Link>

                <Link href="#" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <span>🏷️</span>
                        <span>Voucher & Promo</span>
                    </div>
                    <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 text-[10px] font-bold rounded">Kupon</span>
                </Link>

                <Link href="#" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <span>🛍️</span>
                        <span>Pesanan</span>
                    </div>
                    <span class="px-2 py-0.5 bg-orange-500 text-white text-[10px] font-bold rounded-full">14</span>
                </Link>

                <Link href="#" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <span>👥</span>
                        <span>Pengguna / Pelanggan</span>
                    </div>
                </Link>

                <Link href="#" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <span>⚙️</span>
                        <span>Pengaturan Toko & Gudang</span>
                    </div>
                </Link>
            </div>

            <!-- Admin Profile at Bottom of Sidebar -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center text-sm">
                        {{ user?.name?.[0] || 'A' }}
                    </div>
                    <div>
                        <span class="text-xs font-bold text-white block">{{ user?.name || 'Admin Toko' }}</span>
                        <span class="text-[10px] text-gray-400 block">Marunda Hub JKT</span>
                    </div>
                </div>
                <Link href="/logout" method="post" as="button" class="text-gray-400 hover:text-red-400 transition text-sm" title="Keluar">
                    🚪
                </Link>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header (Matching Figma) -->
            <header class="bg-white border-b border-gray-200 h-20 px-8 flex items-center justify-between sticky top-0 z-40 shadow-sm">
                <div class="flex-1 max-w-xl">
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Cari pesanan, nama produk, atau SKU alat pancing..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition"
                        />
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="hidden sm:flex items-center space-x-2 text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-2 rounded-xl border border-gray-200">
                        <span>📅</span>
                        <span>23 September 2026</span>
                    </div>

                    <Link href="/" class="px-4 py-2 text-xs font-bold text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-xl transition border border-orange-200 flex items-center space-x-1.5">
                        <span>🌐</span>
                        <span>Lihat Toko Publik</span>
                    </Link>

                    <button class="relative p-2 text-gray-600 hover:text-orange-500 rounded-xl hover:bg-gray-100 transition">
                        🔔
                        <span class="absolute top-1 right-1 w-2 h-2 bg-orange-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 p-8 space-y-8">
                <slot />
            </main>
        </div>
    </div>
</template>
