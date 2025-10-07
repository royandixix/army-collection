<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksis', 'penjualan_id')) {
                $table->unsignedBigInteger('penjualan_id')->nullable()->after('id');
                $table->foreign('penjualan_id')
                      ->references('id')
                      ->on('penjualans')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'penjualan_id')) {
                $table->dropForeign(['penjualan_id']);
                $table->dropColumn('penjualan_id');
            }
        });
    }
};
