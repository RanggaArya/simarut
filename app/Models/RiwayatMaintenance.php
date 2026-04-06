<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as RelationsBelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class RiwayatMaintenance extends Model
{
  protected $table = 'riwayat_maintenances';
  protected $fillable = [
    'perangkat_id',
    'user_id',
    'lokasi_id',
    'nama_pemilik',
    'status_akhir',
    'catatan',
    'foto',
    'deskripsi',
    'tanggal_maintenance',
  ];
  protected $casts = [
    'tanggal_maintenance' => 'date',
    'foto' => 'array',
  ];

  public function perangkats(): BelongsTo {
    return $this->belongsTo(Perangkat::class, 'perangkat_id');
  }
    public function perangkat(): BelongsTo {
    return $this->belongsTo(Perangkat::class, 'perangkat_id');
  }
  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }

  public function lokasi(): BelongsTo {
    return $this->belongsTo(Lokasi::class);
  }
  
  public function maintenanceTypes(){
    return $this->belongsToMany(MaintenanceType::class, 'maintenance_type_riwayat');
  }

  public function komponens(){
    return $this->belongsToMany(Komponen::class, 'komponen_riwayat')
    ->withPivot('aksi');
  }

  public function komponenDetails(){
    return $this->hasMany(KomponenRiwayat::class, 'riwayat_maintenance_id')
    ->with('komponen');
  }

  public function getKomponenSummaryAttribute(): string {
    if(! $this->relationLoaded('komponenDetails')) {
      $this->load('komponenDetails.komponen');
    }
    return $this -> komponenDetails
    ->map(function ($row){
      $nama = $row->komponen?->nama ?? '-';
      $aksi =$row->aksi ?: '-';
      $ket = trim((string) $row->keterangan);
      return $ket ? "{$nama} ->{$aksi} ({$ket})":"{$nama} -> {$aksi}";
    })
    ->join('; ');
  }
}