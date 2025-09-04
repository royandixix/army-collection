<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->enum('status', ['pending','diproses','selesai','batal','lunas'])->default('pending')->change();
        });
    }

    public function down(): void {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->enum('status', ['pending','lunas','batal'])->default('pending')->change();
        });
    }
};
