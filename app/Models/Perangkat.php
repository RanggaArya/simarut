<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Perangkat extends Model
{
  use HasFactory;
  protected $table = 'perangkats';

  protected $fillable = [
        'lokasi_id',
        'nomor_inventaris',
        'kategori_id',
        'jenis_id',
        'nama_perangkat',
        'merek_alat',
        'kondisi_id',
        'tanggal_pengadaan',
        'tanggal_supervisi',
        'tahun_pengadaan',
        'sumber_pendanaan',
        'harga_beli',
        'keterangan',
        'status_id',
        'created_by',
        'updated_by',
  ];

  protected $casts = [
        'tanggal_pengadaan' => 'date',
        'tanggal_supervisi' => 'date',
        'harga_beli' => 'integer',
  ];

  public function lokasi(): BelongsTo
  {
    return $this->belongsTo(Lokasi::class);
  }
  public function kategori(): BelongsTo
  {
    return $this->belongsTo(Kategori::class);
  }
  public function jenis(): BelongsTo
  {
    return $this->belongsTo(Jenis::class, 'jenis_id');
  }
  public function kondisi(): BelongsTo
  {
    return $this->belongsTo(Kondisi::class);
  }
  public function status(): BelongsTo
  {
    return $this->belongsTo(Status::class);
  }
  public function riwayatMaintenances(): HasMany
    {
        return $this->hasMany(RiwayatMaintenance::class);
    }
    public function maintenanceTerakhir(): HasOne
    {
        return $this->hasOne(RiwayatMaintenance::class)->latestOfMany();
    }
    public function scopeAktif(Builder $q): Builder
    {
        return $q->whereHas('status', fn($qq) => $qq->where('nama_status', 'Aktif'));
    }
    public function scopePerbaikan(Builder $q): Builder
    {
        return $q->whereHas('status', fn($qq) => $qq->where('nama_status', 'Perbaikan'));
    }
    public function peminjamans()
    {
        return $this->hasMany(\App\Models\Peminjaman::class);
    }
}
