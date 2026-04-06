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
        Schema::create('riwayat_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_id')->constrained('perangkats')->onDelete('cascade');
            $table->string('deskripsi')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_maintenance')->nullable()->index('tanggal_maintenance');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->nullOnDelete();
            $table->string('nama_pemilik')->nullable();
            $table->enum('status_akhir', ['berfungsi', 'berfungsi_sebagian', 'tidak_berfungsi'])->nullable()->index('status_akhir');
            $table->text('catatan')->nullable();
            $table->json('foto')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_maintenances');
    }
};
