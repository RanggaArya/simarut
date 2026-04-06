<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perangkats', function (Blueprint $table) {
            $table->id();

            // 1. Lokasi & Inventaris
            $table->foreignId('lokasi_id')->constrained('lokasis')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nomor_inventaris')->unique()->nullable();

            // 2. Klasifikasi Barang (Kategori & Jenis)
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->nullOnDelete();
            
            // --- INI KITA TAMBAHKAN LAGI (WAJIB) ---
            $table->foreignId('jenis_id')->nullable()->constrained('jenis_perangkats')->nullOnDelete();

            // 3. Identitas Fisik
            $table->string('nama_perangkat'); 
            $table->string('merek_alat')->nullable();
            $table->foreignId('kondisi_id')->nullable()->constrained('kondisis')->nullOnDelete();

            // 4. Tanggal & Waktu
            $table->date('tanggal_pengadaan')->nullable();
            $table->date('tanggal_supervisi')->nullable();
            $table->year('tahun_pengadaan')->nullable();

            // 5. Keuangan
            $table->string('sumber_pendanaan')->nullable();
            $table->bigInteger('harga_beli')->nullable();

            // 6. Lainnya
            $table->text('keterangan')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('statuses')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkats');
    }
};