<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 Buat user test (hindari duplicate)
        \App\Models\User::firstOrCreate(
            ['username' => 'testuser'],
            [
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        // 🔹 Jalankan seeder lain
        $this->call([
            KategoriSeeder::class,
            DummyPenjualanSeeder::class,
        ]);
    }
}
