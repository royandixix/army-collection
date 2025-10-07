<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Penjualan;

class DummyPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Buat user admin (hindari duplicate)
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 2️⃣ Buat pelanggan
        $pelanggan = Pelanggan::firstOrCreate(
            ['email' => 'pelanggan@example.com'],
            [
                'nama' => 'Pelanggan Satu',
                'alamat' => 'Jl. Contoh',
                'no_hp' => '08123456789',
                'user_id' => $user->id,
            ]
        );

        // 3️⃣ Buat penjualan dummy
        Penjualan::firstOrCreate(
            [
                'pelanggan_id' => $pelanggan->id,
                'user_id' => $user->id,
                'total' => 200000,
            ],
            [
                'tanggal' => now(),
                'status' => 'lunas',
            ]
        );
    }
}
