<?php

namespace Database\Seeders;

use App\Enums\VoucherType;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::create([
            'code' => 'FISHMART50',
            'description' => 'Diskon 50% hingga Rp50.000',
            'type' => VoucherType::Percent,
            'value' => 50,
            'min_purchase' => 100000,
            'max_discount' => 50000,
            'quota' => 100,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(30),
            'is_active' => true,
        ]);

        Voucher::create([
            'code' => 'KIRIMGRATIS',
            'description' => 'Potongan ongkir Rp20.000',
            'type' => VoucherType::Fixed,
            'value' => 20000,
            'min_purchase' => 150000,
            'quota' => 200,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(30),
            'is_active' => true,
        ]);
    }
}
