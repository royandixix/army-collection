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
        Schema::table('user_alamats', function (Blueprint $table) {
            $table->string('nama_penerima')->after('label');
            $table->string('no_hp', 20)->after('nama_penerima');
        });
    }

    public function down(): void
    {
        Schema::table('user_alamats', function (Blueprint $table) {
            $table->dropColumn(['nama_penerima', 'no_hp']);
        });
    }
};
