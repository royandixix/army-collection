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
        // 1. Buat user admin
        $user = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Buat pelanggan
        $pelanggan = Pelanggan::firstOrCreate([
            'email' => 'pelanggan@example.com',
        ], [
            'nama' => 'Pelanggan Satu',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '08123456789',
            'user_id' => $user->id,
        ]);

        // 3. Buat penjualan
        Penjualan::create([
            'pelanggan_id' => $pelanggan->id,
            'user_id' => $user->id,
            'tanggal' => now(),
            'total' => 200000,
            'status' => 'lunas',
        ]);
    }
}
