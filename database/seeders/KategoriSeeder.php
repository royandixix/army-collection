<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
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
        ];

        foreach ($data as $name) {
            Kategori::create(['name' => $name]);
        }
    }
}
