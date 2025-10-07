<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel kategori
        Kategori::truncate();

        // Nyalakan lagi foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Isi data kategori
        $data = [
            'Pakaian',
            'Aksesoris',
            'Sepatu',
            'Topi',
            'Rompi',
            'Tas',
            'Lainnya',
        ];

        foreach ($data as $name) {
            Kategori::create(['name' => $name]);
        }
    }
}
