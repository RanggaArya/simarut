<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perangkats', function (Blueprint $table) {
            // Tambahkan setelah kolom harga (sesuaikan 'harga' dengan nama kolommu yang ada)
            $table->unsignedBigInteger('harga_total')->nullable()->after('harga_beli');
            $table->integer('masa_pakai_bulan')->nullable()->comment('Masa pakai dalam bulan')->after('harga_total');
        });
    }

    public function down(): void
    {
        Schema::table('perangkats', function (Blueprint $table) {
            $table->dropColumn(['harga_total', 'masa_pakai_bulan']);
        });
    }
};
