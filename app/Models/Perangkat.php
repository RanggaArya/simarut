<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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
        'harga_total', 
        'masa_pakai_bulan',
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

  // 1. Indikator Pintar: Apakah aset ini nilainya > Rp 2 Juta?
    public function getIsKenaPenyusutanAttribute(): bool
    {
        $basis_harga = $this->harga_total ?? $this->harga_beli ?? 0;
        return $basis_harga > 2000000;
    }

    // 2. Accessor "Masa Pakai Aktif"
    public function getMasaPakaiAktifAttribute(): int
    {
        // Jika harga <= 2 Juta, masa pakai dianggap 0 (Tidak disusutkan)
        if (!$this->is_kena_penyusutan) return 0;
        
        return $this->masa_pakai_bulan ?? optional($this->kategori)->masa_pakai_bulan ?? 0;
    }

    // 3. Accessor: Hitung Bulan Terpakai
    public function getBulanTerpakaiAttribute(): int
    {
        if (!$this->tanggal_pengadaan) return 0;
        $pengadaan = \Carbon\Carbon::parse($this->tanggal_pengadaan)->startOfMonth();
        $sekarang = \Carbon\Carbon::now()->startOfMonth();
        if ($pengadaan->greaterThan($sekarang)) return 0;
        return $pengadaan->diffInMonths($sekarang);
    }

    // 4. Accessor: Hitung Sisa Masa Pakai
    public function getSisaMasaPakaiAttribute(): int
    {
        $mp = $this->masa_pakai_aktif; 
        if (!$mp) return 0;
        
        $sisa = $mp - $this->bulan_terpakai;
        return max(0, $sisa);
    }

    // 5. Accessor: Total Penyusutan
    public function getTotalPenyusutanAttribute(): float
    {
        // Jika harga <= 2 Juta, penyusutan = Rp 0
        if (!$this->is_kena_penyusutan) return 0;

        $basis_harga = $this->harga_total ?? $this->harga_beli ?? 0;
        $mp = $this->masa_pakai_aktif;

        if (!$basis_harga || !$mp || $mp <= 0) {
            return 0;
        }
        if ($this->bulan_terpakai >= $mp) {
            return (float) $basis_harga;
        }
        return ($basis_harga / $mp) * $this->bulan_terpakai;
    }

    // 6. Accessor: Harga Residu
    public function getHargaResiduAttribute(): float
    {
        $basis_harga = $this->harga_total ?? $this->harga_beli ?? 0;
        
        // Jika harga <= 2 Juta, harga residu = harga beli utuh (tidak menyusut)
        if (!$this->is_kena_penyusutan) return (float) $basis_harga;

        $mp = $this->masa_pakai_aktif;

        if (!$basis_harga || !$mp || $mp <= 0) {
            return (float) $basis_harga;
        }
        $residu = $basis_harga - $this->total_penyusutan;
        return max(0, round($residu));
    }

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
