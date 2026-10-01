<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::create([
            'title' => 'Eksplorasi Laut Lepas & Pesisir Nusantara',
            'subtitle' => 'Temukan perlengkapan pancing profesional terbaik dengan garansi keaslian 100%.',
            'image' => 'banners/hero-1.jpg',
            'cta_label' => 'Belanja Sekarang',
            'cta_url' => '/produk',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Koleksi Reel & Joran Premium',
            'subtitle' => 'Diskon spesial akhir pekan untuk produk pilihan Shimano dan Daiwa.',
            'image' => 'banners/hero-2.jpg',
            'cta_label' => 'Lihat Promo',
            'cta_url' => '/produk?sort=latest',
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }
}
