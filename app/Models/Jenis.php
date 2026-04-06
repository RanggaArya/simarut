<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jenis extends Model
{
    protected $table = 'jenis_perangkats';
    protected $fillable = [
        'nama_jenis',
        'prefix',
        'kode_jenis',
    ];  

    public function perangkats(): BelongsTo
    {
        return $this->belongsTo(Perangkat::class);
    }

    public function perangkat(): BelongsTo
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id');
    }
}
