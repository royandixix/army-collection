<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom user_id ke tabel penjualans.
     */
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('pelanggan_id')
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Hapus kolom user_id dari tabel penjualans saat rollback.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
