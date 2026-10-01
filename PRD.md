# PRD: FishMart Indonesia (E-Commerce Alat Pancing)

| Item | Isi |
|---|---|
| Versi | 1.0 (Draft) |
| Tanggal | 30 September 2026 |
| Tim | Kelompok Mirink |
| Sumber desain | Figma "KELOMPOK MIRINK" → page *e-commerce Fishmart Indonesia* |
| Status | Draft, perlu divalidasi terhadap desain Figma sebelum implementasi |

> **Catatan untuk agent AI:** Dokumen ini adalah sumber kebenaran fitur. Untuk detail visual (warna, spacing, tipografi) selalu rujuk frame Figma via MCP dan `docs/DESIGN-TOKENS.md`. Jika PRD dan Figma bertentangan, hentikan dan tanyakan kepada developer.

---

## 1. Ringkasan Produk

FishMart Indonesia adalah toko online khusus perlengkapan memancing (joran, reel, umpan, pakaian, sepatu, aksesori) yang menyasar pemancing di Indonesia, dari pemula sampai kompetitif, untuk pemancingan laut lepas, pesisir, dan air tawar.

Platform terdiri dari dua sisi:
1. **Storefront (customer):** menjelajah, mencari, membeli produk.
2. **Admin Dashboard:** mengelola produk, pesanan, pelanggan, dan memantau penjualan.

## 2. Tujuan dan Metrik Keberhasilan

| Tujuan | Metrik |
|---|---|
| Customer dapat menemukan produk dengan cepat | Pencarian dan filter mengembalikan hasil < 1 detik untuk 1.000 produk |
| Customer dapat menyelesaikan pembelian | Alur Homepage → Checkout selesai tanpa error pada semua skenario utama |
| Admin dapat mengelola toko tanpa developer | CRUD produk, pesanan, dan promo berjalan penuh dari dashboard |
| Tampilan sesuai desain | Kesesuaian visual dengan Figma pada breakpoint desktop dan mobile |

## 3. Peran Pengguna

| Role | Deskripsi | Akses |
|---|---|---|
| **Guest** | Pengunjung belum login | Lihat homepage, katalog, detail produk; keranjang (session) |
| **Customer** | Pengguna terdaftar | Semua akses guest + checkout, riwayat pesanan, wishlist, ulasan, alamat |
| **Admin** | Pengelola toko | Dashboard admin penuh |

## 4. Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.3+ |
| Frontend | Inertia.js + Vue 3 (Composition API, `<script setup>`), TypeScript opsional |
| Styling | Tailwind CSS |
| Komponen UI | shadcn-vue (khususnya untuk area admin) |
| Grafik | Chart.js atau ApexCharts |
| Database | MySQL 8 |
| Auth | Laravel Starter Kit (Fortify) + role |
| Pembayaran | Midtrans Snap (sandbox untuk pengembangan) |
| Ongkir | RajaOngkir / Komerce API |
| Testing | Pest |

## 5. Ruang Lingkup

### Termasuk (In Scope, MVP)
- Homepage, Katalog, Detail Produk, Keranjang & Checkout, Admin Dashboard.
- Registrasi, login, manajemen profil dan alamat.
- Pembayaran online dan perhitungan ongkir.
- Flash sale dengan hitung mundur.
- Ulasan dan rating produk.

### Tidak Termasuk (Out of Scope, versi ini)
- Aplikasi mobile native.
- Marketplace multi-seller.
- Live chat real-time.
- Program afiliasi dan loyalty point.
- Multi-bahasa dan multi-mata uang (hanya Bahasa Indonesia, Rupiah).

## 6. Peta Desain Figma ke Halaman

| Frame Figma | Halaman | Route | File Vue |
|---|---|---|---|
| FishMart Indonesia - Homepage | Beranda | `/` | `pages/Home.vue` |
| FishMart Indonesia - Katalog Produk Alat Pancing | Katalog | `/produk` | `pages/Products/Index.vue` |
| FishMart Indonesia - Detail Produk Shimano | Detail produk | `/produk/{slug}` | `pages/Products/Show.vue` |
| FishMart Indonesia - Keranjang & Checkout | Keranjang & Checkout | `/keranjang`, `/checkout` | `pages/Cart/Index.vue`, `pages/Checkout/Index.vue` |
| FishMart Indonesia - Admin Dashboard (Full) | Dashboard admin | `/admin/*` | `pages/Admin/*` |

Link Figma tiap frame: *(isi setelah MCP tersambung)*

---

## 7. Kebutuhan Fungsional

### 7.1 Komponen Global (semua halaman storefront)

| ID | Kebutuhan |
|---|---|
| G-01 | **Navbar:** logo FishMart, kolom pencarian produk, ikon keranjang dengan badge jumlah item, akses login/akun, navigasi kategori utama. |
| G-02 | Pencarian menampilkan saran (autocomplete) dan mengarah ke katalog dengan query `?q=`. |
| G-03 | **Footer:** info toko, tautan bantuan, kategori, kontak, dan metode pembayaran/pengiriman yang didukung. |
| G-04 | Layout responsif (mobile, tablet, desktop). |
| G-05 | Komponen reusable: `ProductCard`, `Button`, `Badge`, `Rating`, `Pagination`, `Breadcrumb`, `Modal`, `Toast`. |

### 7.2 Homepage

Bagian-bagian sesuai urutan di desain:

| ID | Bagian | Kebutuhan |
|---|---|---|
| H-01 | **Hero Banner** | Banner besar dengan headline (contoh: "Eksplorasi Laut Lepas & Pesisir Nusantara"), sub-teks, dan tombol CTA ke katalog. Konten dapat dikelola admin. |
| H-02 | **Cari Berdasarkan Kategori** | Grid ikon/gambar kategori (joran, reel, umpan, pakaian, sepatu, aksesori, dll). Klik mengarah ke katalog terfilter. |
| H-03 | **Flash Sale** | Blok promo berlatar gelap dengan hitung mundur (jam:menit:detik), daftar produk diskon dengan harga coret, persentase diskon, dan indikator stok terjual. Otomatis hilang saat periode berakhir. |
| H-04 | **Produk Rekomendasi Terlaris** | Grid produk dengan tab/filter kategori, urut berdasarkan penjualan terbanyak. Tautan "Lihat Semua". |
| H-05 | **Produk Terbaru Masuk Gudang** | Grid produk terbaru berdasarkan tanggal dibuat. |
| H-06 | **Keunggulan Toko** | Strip nilai jual (pengiriman, produk asli, garansi, layanan pelanggan). |

### 7.3 Katalog Produk

| ID | Kebutuhan |
|---|---|
| K-01 | Judul halaman dan breadcrumb (Beranda > Katalog). |
| K-02 | **Sidebar filter:** kategori, merek, rentang harga (min–maks), rating, status stok/diskon. |
| K-03 | **Pengurutan:** terbaru, terlaris, harga terendah, harga tertinggi, rating tertinggi. |
| K-04 | Grid `ProductCard`: gambar, nama, merek, harga, harga coret + badge diskon (jika ada), rating, jumlah terjual, tombol tambah ke keranjang. |
| K-05 | **Pagination** (12 atau 24 produk per halaman), filter tersimpan di URL query agar bisa dibagikan. |
| K-06 | Filter diterapkan tanpa reload penuh (Inertia partial reload). |
| K-07 | Tampilan kosong jika tidak ada hasil, dengan saran mereset filter. |
| K-08 | Tombol "Hapus semua filter". |

### 7.4 Detail Produk

| ID | Kebutuhan |
|---|---|
| D-01 | **Galeri gambar:** gambar utama + thumbnail; klik thumbnail mengganti gambar utama; zoom opsional. |
| D-02 | **Info utama:** nama, merek, rating dan jumlah ulasan, jumlah terjual, harga, harga coret dan diskon, status stok. |
| D-03 | **Varian** (jika ada): pilihan ukuran/panjang/rasio, harga dan stok mengikuti varian. |
| D-04 | **Kuantitas** dengan tombol +/-; dibatasi maksimum sesuai stok. |
| D-05 | Tombol **Tambah ke Keranjang** dan **Beli Sekarang** (langsung ke checkout). |
| D-06 | Tombol wishlist (perlu login). |
| D-07 | **Tab deskripsi & spesifikasi:** tabel spesifikasi teknis (mis. panjang, bobot, material, line rating). |
| D-08 | **Ulasan pelanggan:** ringkasan rating (bintang 1–5 beserta distribusi), daftar ulasan, foto ulasan. Hanya pembeli dengan pesanan selesai yang dapat menulis ulasan. |
| D-09 | **Produk terkait:** carousel/grid produk dari kategori atau merek yang sama. |
| D-10 | Estimasi ongkir berdasarkan kota tujuan (opsional MVP). |

### 7.5 Keranjang

| ID | Kebutuhan |
|---|---|
| C-01 | Daftar item: gambar, nama, varian, harga satuan, kuantitas (editable), subtotal, tombol hapus. |
| C-02 | Checkbox pilih item untuk di-checkout. |
| C-03 | Keranjang guest disimpan di session dan **digabung** ke keranjang akun saat login. |
| C-04 | Validasi stok real-time; item yang habis ditandai dan tidak dapat di-checkout. |
| C-05 | **Ringkasan pesanan:** subtotal, diskon/voucher, estimasi ongkir, total. |
| C-06 | Input **kode voucher** dengan validasi (masa berlaku, minimum belanja, kuota). |
| C-07 | Tombol "Lanjut ke Checkout" (memerlukan login). |

### 7.6 Checkout

| ID | Kebutuhan |
|---|---|
| CO-01 | **Alamat pengiriman:** pilih dari alamat tersimpan atau tambah baru (provinsi, kota/kabupaten, kecamatan, kode pos, detail alamat, penerima, nomor telepon). |
| CO-02 | **Kurir dan layanan:** pilihan kurir (JNE, J&T, SiCepat, dll) dengan tarif dan estimasi tiba dari API ongkir. Perhitungan berdasarkan berat total. |
| CO-03 | **Metode pembayaran:** transfer bank/Virtual Account, e-wallet, QRIS via Midtrans. |
| CO-04 | Ringkasan pesanan dan total akhir. |
| CO-05 | Tombol "Buat Pesanan": membuat order berstatus *Menunggu Pembayaran*, membuka Midtrans Snap. |
| CO-06 | Webhook Midtrans memperbarui status pembayaran otomatis. |
| CO-07 | Halaman sukses/gagal/pending setelah pembayaran. |
| CO-08 | Pesanan yang tidak dibayar dalam 24 jam otomatis **dibatalkan** dan stok dikembalikan (scheduled job). |

### 7.7 Akun Customer

| ID | Kebutuhan |
|---|---|
| A-01 | Registrasi, login, logout, lupa dan reset password, verifikasi email. |
| A-02 | Profil: ubah nama, email, telepon, password. |
| A-03 | Buku alamat: tambah, ubah, hapus, jadikan alamat utama. |
| A-04 | **Riwayat pesanan:** daftar, filter status, detail pesanan, nomor resi, tombol bayar ulang/batalkan, konfirmasi pesanan diterima. |
| A-05 | Wishlist. |

### 7.8 Admin Dashboard

| ID | Modul | Kebutuhan |
|---|---|---|
| AD-01 | **Ringkasan** | Kartu statistik: total penjualan, jumlah pesanan, pelanggan baru, produk terjual. Filter periode (hari ini, 7 hari, 30 hari, kustom). |
| AD-02 | **Grafik penjualan** | Grafik batang/garis penjualan per periode (harian/bulanan). |
| AD-03 | **Produk terlaris & stok menipis** | Tabel produk terlaris dan peringatan stok di bawah ambang batas. |
| AD-04 | **Pesanan terbaru** | Tabel pesanan terbaru dengan status berwarna. |
| AD-05 | **Manajemen produk** | Tabel dengan pencarian, filter, pagination; tambah/ubah/hapus produk; upload banyak gambar; varian; spesifikasi; stok; harga; status aktif. |
| AD-06 | **Kategori & merek** | CRUD kategori (dengan ikon/gambar) dan merek. |
| AD-07 | **Manajemen pesanan** | Daftar dan detail pesanan, ubah status (Diproses → Dikirim → Selesai), input nomor resi, batalkan pesanan, cetak invoice/label. |
| AD-08 | **Flash sale & voucher** | Atur produk flash sale (harga, kuota, waktu mulai/selesai) dan CRUD voucher. |
| AD-09 | **Pelanggan** | Daftar pelanggan, riwayat pembelian, nonaktifkan akun. |
| AD-10 | **Ulasan** | Moderasi ulasan (setujui/sembunyikan). |
| AD-11 | **Banner homepage** | Kelola konten hero banner. |
| AD-12 | **Laporan** | Laporan penjualan per periode, dapat diekspor (CSV/Excel). |
| AD-13 | Sidebar navigasi, header dengan profil admin, dan tema sesuai desain. |

---

## 8. Aturan Bisnis

1. Stok berkurang saat pesanan **dibuat** (direservasi) dan dikembalikan bila pesanan dibatalkan/kedaluwarsa.
2. Tidak boleh checkout melebihi stok; validasi ulang di server saat membuat pesanan.
3. Harga final selalu dihitung di **server**, bukan dari nilai yang dikirim browser.
4. Harga flash sale berlaku hanya pada periode aktif dan selama kuota tersedia; setelahnya kembali ke harga normal.
5. Satu voucher per pesanan; tidak dapat digabung kecuali ditentukan lain.
6. Ongkir dihitung dari berat total (gram) × tarif kurir; setiap produk wajib punya berat.
7. Ulasan hanya untuk pesanan berstatus *Selesai*, satu ulasan per produk per pesanan.
8. Alur status pesanan: `pending_payment` → `paid` → `processing` → `shipped` → `completed`; cabang: `cancelled`, `expired`, `refunded`.
9. Admin tidak dapat menghapus produk yang sudah punya riwayat pesanan; gunakan *soft delete*/nonaktifkan.

## 9. Model Data (Ringkas)

| Tabel | Kolom Utama |
|---|---|
| `users` | id, name, email, password, phone, role (`customer`/`admin`), timestamps |
| `addresses` | id, user_id, recipient, phone, province, city, district, postal_code, detail, is_default |
| `categories` | id, name, slug, icon/image, parent_id |
| `brands` | id, name, slug, logo |
| `products` | id, category_id, brand_id, name, slug, description, price, sale_price, stock, weight_gram, sold_count, rating_avg, is_active, soft deletes |
| `product_images` | id, product_id, path, sort_order, is_primary |
| `product_variants` | id, product_id, name, sku, price, stock |
| `product_specs` | id, product_id, label, value |
| `flash_sales` | id, name, starts_at, ends_at |
| `flash_sale_items` | id, flash_sale_id, product_id, sale_price, quota, sold |
| `carts` / `cart_items` | user_id/session_id; cart_id, product_id, variant_id, qty |
| `vouchers` | id, code, type (`percent`/`fixed`), value, min_purchase, max_discount, quota, starts_at, ends_at |
| `orders` | id, invoice_no, user_id, address_snapshot, courier, shipping_service, shipping_cost, subtotal, discount, total, status, tracking_no, paid_at, expires_at |
| `order_items` | id, order_id, product_id, variant_id, name_snapshot, price_snapshot, qty |
| `payments` | id, order_id, method, gateway_ref, status, amount, payload (json) |
| `reviews` | id, user_id, product_id, order_id, rating, comment, images, is_visible |
| `wishlists` | user_id, product_id |
| `banners` | id, title, subtitle, image, cta_label, cta_url, is_active |

Relasi utama: Category 1—N Product, Brand 1—N Product, Product 1—N Image/Variant/Spec/Review, User 1—N Order, Order 1—N OrderItem, Order 1—1 Payment.

## 10. Kebutuhan Non-Fungsional

| Aspek | Kebutuhan |
|---|---|
| Performa | LCP < 2,5 detik pada koneksi 4G; gambar dioptimasi (WebP, lazy load); eager loading untuk mencegah N+1 |
| Keamanan | CSRF, validasi via Form Request, otorisasi via Policy/Gate, rate limiting login, hashing password, verifikasi signature webhook Midtrans, upload dibatasi tipe dan ukuran |
| Responsif | Mobile-first: 360px, 768px, 1024px, 1440px |
| SEO | Meta title/description dinamis, slug ramah URL, sitemap |
| Aksesibilitas | Kontras warna memadai, alt text gambar, navigasi keyboard |
| Kualitas kode | Laravel Pint, ESLint/Prettier, test Pest untuk alur kritis (auth, keranjang, checkout, stok, webhook) |
| Keandalan | Queue untuk email dan webhook, scheduled job untuk pesanan kedaluwarsa, backup database berkala |

## 11. Panduan Desain (Design Tokens)

Isi lengkap di `docs/DESIGN-TOKENS.md` setelah diekstrak dari Figma. Dari tampilan desain, identitas visual:

- **Warna utama:** biru navy gelap (header, footer, blok flash sale, sidebar admin).
- **Warna aksen:** oranye (tombol CTA, badge diskon, harga promo).
- **Latar:** putih/abu sangat muda untuk area konten.
- **Gaya:** kartu produk bersih dengan sudut membulat, grid rapat, hierarki informasi jelas.
- **Tipografi:** *(isi dari Figma)*
- **Radius / shadow / spacing:** *(isi dari Figma)*

## 12. Rencana Rilis

| Milestone | Cakupan | Estimasi |
|---|---|---|
| M1 Fondasi | Setup project, auth + role, layout, komponen dasar, migration + seeder | Minggu 1 |
| M2 Katalog | Homepage, katalog + filter, detail produk | Minggu 2 |
| M3 Transaksi | Keranjang, checkout, ongkir, Midtrans, riwayat pesanan | Minggu 3–4 |
| M4 Admin | Dashboard, produk, pesanan, flash sale, voucher, laporan | Minggu 4–5 |
| M5 Finalisasi | Testing, perbaikan responsif, optimasi, deploy | Minggu 6 |

## 13. Kriteria Penerimaan Utama

1. Customer dapat mendaftar, login, mencari produk, memfilter, membuka detail, menambah ke keranjang, checkout, dan membayar di sandbox Midtrans.
2. Status pesanan berubah otomatis setelah pembayaran dan admin dapat memprosesnya sampai selesai.
3. Stok konsisten pada pembelian bersamaan, pembatalan, dan kedaluwarsa.
4. Admin dapat mengelola seluruh data tanpa menyentuh database.
5. Semua halaman sesuai Figma dan berfungsi baik di mobile dan desktop.
6. Seluruh test kritis lulus.

## 14. Pertanyaan Terbuka

- [ ] Apakah proyek ini produksi nyata atau tugas/prototipe (menentukan kedalaman integrasi pembayaran dan ongkir)?
- [ ] Apakah perlu varian produk (ukuran/rasio) atau cukup satu harga per produk?
- [ ] Kurir apa saja yang didukung, dan lokasi gudang asal pengiriman?
- [ ] Apakah admin perlu beberapa level (super admin, staf gudang)?
- [ ] Apakah tampilan mobile sudah didesain di Figma, atau hanya desktop?
- [ ] Sumber gambar dan data produk awal (seeder dummy atau data asli)?

## 15. Urutan Prompt untuk Agent AI (Panduan Implementasi)

1. "Baca `docs/PRD.md` dan `docs/DESIGN-TOKENS.md`. Buat migration, model, dan seeder sesuai bagian 9."
2. "Buat layout global (navbar, footer) dan komponen reusable sesuai G-01 s.d. G-05 dengan mengacu ke frame Figma Homepage."
3. "Implementasikan Homepage (H-01 s.d. H-06) dari frame Figma [link]."
4. "Implementasikan Katalog (K-01 s.d. K-08)."
5. "Implementasikan Detail Produk (D-01 s.d. D-10)."
6. "Implementasikan Keranjang dan Checkout, lalu integrasi Midtrans sandbox."
7. "Implementasikan modul Admin satu per satu (AD-01 s.d. AD-13)."

Setelah tiap langkah: review kode, tes manual, tulis test Pest, lalu commit.
