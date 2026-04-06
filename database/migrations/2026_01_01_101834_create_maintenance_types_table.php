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
        Schema::create('maintenance_types', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });
        Schema::create('maintenance_type_riwayat', function (Blueprint $table){
          $table->id();
          $table->foreignId('riwayat_maintenance_id')->constrained('riwayat_maintenances')->cascadeOnDelete();
          $table->foreignId('maintenance_type_id')->constrained('maintenance_types')->cascadeOnDelete();
          $table->unique(['riwayat_maintenance_id', 'maintenance_type_id'], 'mtri_rm_mt_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_types');
        Schema::dropIfExists('maintenance_type_riwayat');
    }
};
