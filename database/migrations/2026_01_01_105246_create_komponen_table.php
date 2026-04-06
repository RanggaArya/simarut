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
        Schema::create('komponens', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });
        Schema::create('komponen_riwayat', function (Blueprint $table){
          $table->id();
          $table->foreignId('riwayat_maintenance_id')->constrained('riwayat_maintenances')->cascadeOnDelete();
          $table->foreignId('komponen_id')->constrained('komponens')->cascadeOnDelete();
          $table->enum('aksi', ['dicek', 'diganti'])->nullable();
          $table->text('keterangan')->nullable();
          $table->unique(['riwayat_maintenance_id', 'komponen_id'], 'kri_rm_km_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen');
        Schema::dropIfExists('komponen_riwayat');
    }
};
