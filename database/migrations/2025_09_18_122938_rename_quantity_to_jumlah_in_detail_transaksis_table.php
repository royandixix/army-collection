<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->renameColumn('quantity', 'jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->renameColumn('jumlah', 'quantity');
        });
    }
};
