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
        Schema::create('penarikan_alats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_id')->nullable()->constrained('perangkats')->onDelete('set null');
            $table->string('nama_perangkat')->nullable();
            $table->string('nomor_inventaris')->nullable();
            $table->string('merek')->nullable();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->onDelete('set null');
            $table->integer('tahun_pengadaan')->nullable();

            $table->date('tanggal_penarikan');
            $table->json('alasan_penarikan');
            $table->text('alasan_lainnya');

            $table->string('tindak_lanjut_tipe')->nullable();
            $table->text('tindak_lanjut_detail')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan_alats');
    }
};
