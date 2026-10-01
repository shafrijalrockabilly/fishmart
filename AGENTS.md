# AGENTS.md: Aturan Proyek FishMart Indonesia

File ini dibaca agent AI (Claude Code, Cursor, Copilot, dll) di awal setiap sesi. Ikuti semua aturan di sini. Jika ada aturan yang bertentangan dengan permintaan developer, **tanyakan dulu**, jangan asal memilih.

---

## 1. Tentang Proyek

FishMart Indonesia adalah e-commerce perlengkapan memancing dengan dua sisi: **storefront** (customer) dan **admin dashboard**.

Dokumen acuan (baca sebelum mengerjakan fitur):

| Dokumen | Fungsi |
|---|---|
| `docs/PRD.md` | Fitur, aturan bisnis, kriteria penerimaan |
| `docs/DATABASE.md` | Skema tabel, relasi, enum, logika data |
| `docs/DESIGN-TOKENS.md` | Warna, font, radius, spacing dari Figma |
| Figma (via MCP) | Sumber kebenaran tampilan visual |

**Urutan prioritas jika ada konflik:** instruksi developer di chat → PRD → DATABASE → Figma → file ini. Bila PRD dan Figma berbeda, berhenti dan tanyakan.

## 2. Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.3+ |
| Frontend | Inertia.js + Vue 3 (`<script setup>`, Composition API) |
| Styling | Tailwind CSS (utility class, tanpa CSS kustom kecuali terpaksa) |
| Komponen UI | shadcn-vue untuk area admin |
| Database | MySQL 8 |
| Pembayaran | Midtrans Snap (sandbox saat development) |
| Ongkir | RajaOngkir / Komerce API |
| Testing | Pest |
| Format kode | Laravel Pint (PHP), ESLint + Prettier (JS/Vue) |

**Jangan menambah library, package, atau framework baru tanpa persetujuan developer.** Jika menurut kamu perlu, jelaskan alasannya dan tunggu jawaban.

## 3. Perintah Penting

```bash
composer install && npm install     # instalasi awal
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed    # reset DB + data contoh (HANYA di lokal)
composer dev                        # jalankan server + queue + vite
php artisan test                    # jalankan test Pest
vendor/bin/pint                     # format PHP
npm run lint                        # lint JS/Vue
npm run build                       # build produksi (cek tidak error)
php artisan storage:link            # link storage publik
```

Sebelum menyatakan tugas selesai, jalankan: `vendor/bin/pint`, `php artisan test`, dan `npm run build`. Laporkan hasilnya apa adanya, termasuk jika ada yang gagal.

## 4. Struktur Folder

```
app/
  Enums/                 # OrderStatus, PaymentStatus, UserRole, VoucherType
  Http/
    Controllers/         # Controller storefront
    Controllers/Admin/   # Controller admin
    Requests/            # Form Request (validasi)
    Middleware/
  Models/
  Policies/
  Services/              # Logika bisnis (CartService, CheckoutService, ShippingService, MidtransService, StockService, PricingService)
  Actions/               # Aksi tunggal opsional (mis. CreateOrder)
  Jobs/                  # ExpireUnpaidOrders, dll
database/
  migrations/  factories/  seeders/
resources/js/
  pages/                 # Halaman Inertia (Home.vue, Products/Index.vue, Admin/...)
  layouts/               # StoreLayout.vue, AdminLayout.vue
  components/
    ui/                  # shadcn-vue
    store/               # ProductCard, HeroBanner, CategoryGrid, FlashSale, dll
    admin/               # StatCard, DataTable, SalesChart, dll
  composables/           # useCart, useCurrency, useCountdown, dll
  types/                 # TypeScript types
docs/                    # PRD, DATABASE, DESIGN-TOKENS
tests/Feature  tests/Unit
```

Sesuaikan dengan struktur bawaan starter kit yang terpasang; jangan memindahkan file bawaan tanpa alasan.

## 5. Konvensi Kode

### Umum
- Bahasa kode (nama variabel, fungsi, kelas, komentar): **Inggris**.
- Teks yang tampil ke pengguna: **Bahasa Indonesia**.
- Format uang tampil: `Rp1.250.000` (helper/composable `useCurrency`, jangan format manual di banyak tempat).
- Satu file, satu tanggung jawab. Pecah jika lebih dari sekitar 200 baris.

### PHP / Laravel
- Ikuti PSR-12 dan aturan Laravel Pint.
- Controller **tipis**: validasi lewat Form Request, logika bisnis di Service/Action, kembalikan `Inertia::render()`.
- Otorisasi lewat **Policy/Gate** dan middleware role; jangan cek role manual di banyak tempat.
- Gunakan **Eloquent + eager loading** (`with()`); waspadai N+1.
- Gunakan **PHP Backed Enum** untuk status, jangan string literal yang tersebar.
- Operasi yang mengubah beberapa tabel (checkout, pembatalan, webhook) wajib dalam `DB::transaction()`.
- Nama tabel `snake_case` jamak, model `PascalCase` tunggal, method `camelCase`.
- Route storefront memakai slug Indonesia (`/produk`, `/keranjang`); route admin di prefix `/admin` dengan middleware `auth`, `verified`, `role:admin`.

### Vue / Frontend
- Gunakan `<script setup lang="ts">` dan Composition API. Tidak memakai Options API.
- Nama komponen `PascalCase`, composable diawali `use`.
- Props diberi tipe; hindari `any`.
- Styling dengan Tailwind. **Warna, font, radius, dan spacing wajib memakai token** dari `docs/DESIGN-TOKENS.md` (didaftarkan di konfigurasi Tailwind/CSS variable). **Dilarang hard-code hex color** di komponen.
- Bangun komponen **reusable** dulu (Button, Badge, ProductCard, Rating, Pagination), baru halaman.
- Gunakan komponen `<Link>` dan `router` dari Inertia untuk navigasi; jangan `<a href>` untuk tautan internal.
- Gambar: `loading="lazy"`, atribut `alt` wajib, ukuran ditentukan agar tidak layout shift.
- Semua halaman **responsif** (mobile-first): uji di 360px, 768px, 1024px, 1440px.

## 6. Aturan Implementasi dari Figma

1. Baca frame Figma yang diminta via MCP sebelum menulis kode. Jika MCP tidak tersedia, minta screenshot atau link, jangan menebak.
2. Pecah frame menjadi komponen; kenali bagian yang berulang (kartu produk, badge, tombol).
3. Cocokkan tata letak, urutan seksi, dan hierarki visual dengan desain.
4. Gunakan data dari database (lewat controller), **bukan data statis** yang ditanam di komponen. Isi awal berasal dari seeder.
5. Jika desain tidak mencakup suatu kondisi (loading, kosong, error, hover, disabled), buat sesuai token yang ada dan **sebutkan asumsi** di laporan.
6. Jangan menambah elemen atau fitur yang tidak ada di desain atau PRD.

## 7. Aturan Keamanan dan Bisnis (Wajib)

Bagian ini paling sering salah pada kode buatan AI. Periksa dua kali.

- **Harga, diskon, ongkir, dan total selalu dihitung di server** dari database. Jangan pernah percaya angka dari browser.
- **Stok:** kurangi di dalam transaction dengan `lockForUpdate()`; kembalikan saat pesanan batal/kedaluwarsa. Lihat `docs/DATABASE.md` bagian 5.
- **Prioritas harga:** flash sale aktif dan kuota tersedia → `sale_price` → `price`.
- **Webhook Midtrans:** verifikasi signature, idempotent (aman dipanggil berulang), perbarui status pesanan di transaction.
- **Otorisasi:** customer hanya boleh melihat pesanan, alamat, dan keranjang miliknya sendiri (cek kepemilikan di Policy).
- **Validasi input** lewat Form Request untuk semua endpoint yang menerima data. Batasi tipe dan ukuran file upload.
- **Mass assignment:** definisikan `$fillable` secara eksplisit; jangan `$guarded = []`.
- **Rahasia:** kunci API (Midtrans, RajaOngkir) hanya di `.env`. Jangan pernah commit `.env` atau menulis kunci ke kode.
- **Rate limiting** pada login, registrasi, dan endpoint voucher.
- Output ke Blade/Vue tidak boleh memakai HTML mentah dari input pengguna (`v-html`) tanpa sanitasi.

## 8. Testing

- Tulis test **Pest** untuk alur kritis: registrasi/login, tambah ke keranjang, validasi stok, perhitungan harga (termasuk flash sale dan voucher), pembuatan pesanan, webhook pembayaran, pembatalan/kedaluwarsa pesanan, otorisasi admin.
- Gunakan Factory, bukan data manual.
- Gunakan mock/fake untuk API eksternal (Midtrans, ongkir); test tidak boleh memanggil layanan asli.
- Test yang gagal **tidak boleh dihapus atau dilewati** demi "membuat hijau". Perbaiki penyebabnya atau laporkan.

## 9. Alur Kerja dengan Developer

1. **Kerjakan satu fitur atau satu halaman per permintaan.** Jangan melebar ke fitur lain.
2. Sebelum coding fitur besar, ringkas rencana dalam beberapa poin (file yang akan dibuat/diubah), lalu lanjutkan bila tidak ada keberatan.
3. Setelah selesai, laporkan singkat: apa yang dibuat, file yang berubah, asumsi yang diambil, hal yang belum dikerjakan, dan cara mengujinya secara manual.
4. Jika instruksi ambigu atau data hilang, **tanyakan**; jangan mengarang.
5. Jangan menyatakan "selesai" bila belum dijalankan/diuji. Katakan dengan jujur bagian yang belum terverifikasi.

### Git
- Satu commit per perubahan logis, memakai Conventional Commits: `feat: ...`, `fix: ...`, `refactor: ...`, `test: ...`, `docs: ...`, `chore: ...`.
- Kerja di branch fitur (`feat/homepage`, `feat/checkout`), bukan langsung di `main`.
- Jangan melakukan `git push --force`, `git reset --hard`, atau menghapus branch tanpa izin.

## 10. Yang TIDAK Boleh Dilakukan

- Menjalankan `migrate:fresh`, `db:wipe`, atau perintah destruktif ke database **selain lokal**.
- Mengedit migration lama yang sudah dijalankan; buat migration baru.
- Mengubah skema database tanpa memperbarui `docs/DATABASE.md`.
- Mengubah fitur atau aturan bisnis tanpa memperbarui `docs/PRD.md` (dengan persetujuan developer).
- Mengedit file di `vendor/`, `node_modules/`, atau hasil build.
- Mengedit `.env`, kredensial, atau file konfigurasi server.
- Menghapus atau menonaktifkan validasi, otorisasi, atau test untuk "menyelesaikan masalah".
- Meninggalkan `dd()`, `dump()`, `console.log`, atau kode debug di hasil akhir.
- Menulis data dummy/hard-code di komponen sebagai pengganti data dari database.
- Membuat fitur di luar cakupan PRD (lihat "Out of Scope").

## 11. Definition of Done

Sebuah fitur dianggap selesai bila:

- [ ] Sesuai PRD (kode kebutuhan yang relevan terpenuhi) dan tampilan sesuai Figma
- [ ] Responsif di mobile dan desktop
- [ ] Ada state loading, kosong, dan error yang wajar
- [ ] Validasi dan otorisasi sudah diterapkan
- [ ] Test Pest untuk logika penting ditambahkan dan lulus
- [ ] `vendor/bin/pint`, `npm run lint`, `npm run build` tanpa error
- [ ] Tidak ada kode debug, kredensial, atau data hard-code
- [ ] Dokumentasi (`PRD.md`/`DATABASE.md`) diperbarui bila ada perubahan
- [ ] Laporan ringkas diberikan ke developer, lalu di-commit

## 12. Urutan Pengerjaan yang Disepakati

1. Fondasi: migration, model, enum, seeder, auth + role
2. Layout global dan komponen reusable
3. Homepage
4. Katalog + filter + pencarian
5. Detail produk
6. Keranjang
7. Checkout + ongkir + Midtrans
8. Riwayat pesanan customer
9. Admin: dashboard, produk, pesanan, flash sale, voucher, laporan
10. Testing menyeluruh, optimasi, deploy

Kerjakan sesuai urutan ini kecuali developer menginstruksikan lain.
