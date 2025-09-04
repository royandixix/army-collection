<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('penjualan_id')->nullable()->after('id');

            // Tambah foreign key
            $table->foreign('penjualan_id')
                ->references('id')
                ->on('penjualans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Drop foreign key dulu baru drop kolomnya
            $table->dropForeign(['penjualan_id']);
            $table->dropColumn('penjualan_id');
        });
    }

    
};
