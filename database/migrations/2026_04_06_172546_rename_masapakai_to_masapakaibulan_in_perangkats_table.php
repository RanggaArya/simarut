<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perangkats', function (Blueprint $table) {
            // Mengganti nama kolom dari masa_pakai menjadi masa_pakai_bulan
            $table->renameColumn('masa_pakai', 'masa_pakai_bulan');
        });
    }

    public function down(): void
    {
        Schema::table('perangkats', function (Blueprint $table) {
            // Mengembalikan nama kolom jika di-rollback
            $table->renameColumn('masa_pakai_bulan', 'masa_pakai');
        });
    }
};