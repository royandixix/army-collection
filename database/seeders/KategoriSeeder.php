<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Pakaian Tempur',
            'Sepatu Tactical',
            'Aksesoris Militer',
            'Perlengkapan Camping',
            'Tas Tactical',
            'Rompi & Armor',
            'Topi & Headgear',
            'Sarung Tangan',
            'Alat Survival',
            'Perlengkapan Tembak',
            'Senjata', // ✅ Tambahan baru
        ];

        foreach ($data as $name) {
            Kategori::firstOrCreate(['name' => $name]);
        }
    }
}
