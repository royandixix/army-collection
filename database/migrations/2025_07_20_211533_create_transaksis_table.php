<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('penjualan_id')->nullable();
            $table->text('alamat');
            $table->enum('metode', ['cod', 'transfer', 'qris']);
            $table->string('bukti_tf')->nullable(); // ✅ tanpa 'after'
            $table->enum('status', ['pending', 'diproses', 'selesai', 'batal'])->default('pending');
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();

            // Foreign key ke penjualans
            $table->foreign('penjualan_id')->references('id')->on('penjualans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};
