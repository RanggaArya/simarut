<?php

namespace App\Filament\Imports\Traits;

use App\Models\Jenis;
use App\Models\Kondisi;
use App\Models\Lokasi;
use App\Models\Status;
use App\Models\Kategori;
use PhpOffice\PhpSpreadsheet\Shared\Date;

trait MapsMaster
{
    protected array $lokasiMap = [];
    protected array $jenisMap = [];
    protected array $statusMap = [];
    protected array $kondisiMap = [];
    protected array $kategoriMap = [];
    protected array $kategoriCodeMap = [];
    
    // MAPPING: Excel Header (lowercase) => Model Attribute Database
    protected array $COLUMN_ALIASES = [
        'no_inventaris'    => 'nomor_inventaris',
        'nomor_asset'      => 'nomor_inventaris',
        'nomor_inventaris' => 'nomor_inventaris',
        'nama_alat'        => 'nama_perangkat',
        'nama_barang'      => 'nama_perangkat',
        'nama_perangkat'   => 'nama_perangkat',
        'jenis'            => 'jenis', 
        'kategori'         => 'kategori_excel',      
        'kode_kategori'    => 'kode_kategori_excel', 
        'merek_alat'       => 'merek_alat', 
        'merek'            => 'merek_alat',
        'tipe'             => 'merek_alat', 
        'kondisi_alat'     => 'kondisi',
        'kondisi'          => 'kondisi',
        'lokasi'           => 'lokasi',
        'tanggal_pengadaan'=> 'tanggal_pengadaan', 
        'tahun_pengadaan'  => 'tahun_pengadaan',
        'sumber_pendanaan' => 'sumber_pendanaan', 
        'sumber'           => 'sumber_pendanaan',
        'harga_beli'       => 'harga_beli',       
        'harga'            => 'harga_beli',
        'keterangan'       => 'keterangan',       
        'catatan'          => 'keterangan',
    ];

    protected function bootMasterMaps(): void
    {
        // Load data ke memori untuk mempercepat proses (Caching sederhana)
        $this->lokasiMap  = Lokasi::pluck('id', 'nama_lokasi')->all();
        $this->statusMap  = Status::pluck('id', 'nama_status')->all();
        $this->kondisiMap = Kondisi::pluck('id', 'nama_kondisi')->all();
        $this->kategoriMap = Kategori::pluck('id', 'nama_kategori')->all();

        $this->kategoriCodeMap = [];
        foreach (Kategori::select('id', 'kode_kategori')->get() as $k) {
            $key = $this->normalizeKategoriKode($k->kode_kategori);
            if ($key !== null) {
                $this->kategoriCodeMap[$key] = (int) $k->id;
            }
        }

        $this->jenisMap = Jenis::all()
            ->pluck('id', 'nama_jenis')
            ->mapWithKeys(fn($id, $name) => [mb_strtolower(trim($name)) => $id])
            ->all();
    }

    // --- HELPER FUNCTIONS ---

    // [PERBAIKAN UTAMA] Gunakan firstOrCreate agar tidak error Duplicate Entry
    protected function getOrCreateId(array &$map, string $modelClass, string $column, $value): ?int
    {
        $name = trim((string)($value ?? ''));
        if ($name === '') return null;

        $lookup = mb_strtolower($name);
        
        // 1. Cek di Cache Array (Memory)
        if (isset($map[$lookup])) return (int)$map[$lookup];

        // 2. Cek/Buat di Database (Aman dari duplikat)
        // firstOrCreate: Cari berdasarkan $column, jika tidak ada create baru.
        $record = $modelClass::firstOrCreate(
            [$column => $name] 
        );

        // Simpan balik ke map memory agar request berikutnya lebih cepat
        $map[$lookup] = (int)$record->id;
        return (int)$record->id;
    }

    protected function parseTanggal($value): ?string
    {
        if (empty($value)) return null;
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Throwable) { return null; }
        }
        try {
            return date('Y-m-d', strtotime((string)$value));
        } catch (\Throwable) { return null; }
    }

    protected function normalizeNomor(?string $v): ?string
    {
        $n = strtoupper(trim((string)$v));
        $empty = ['', 'NAN', 'NA', 'N/A', '-', '—', '0', '#N/A', 'NULL'];
        return ($n === '' || in_array($n, $empty, true)) ? null : $n;
    }

    public function normalizeRowKeys(array $row): array
    {
        $out = [];
        foreach ($row as $k => $v) {
            $key = strtolower(trim((string)$k));
            $key = str_replace([' ', '-'], '_', $key);
            $finalKey = $this->COLUMN_ALIASES[$key] ?? $key;
            $out[$finalKey] = $v;
        }
        return $out;
    }

    protected function normalizeDeviceName(string $name): string
    {
        return preg_replace('/\s+/u', ' ', mb_strtolower(trim($name)));
    }

    // --- KATEGORI RESOLVER (FIXED DUPLICATE ISSUE) ---
    
    protected function resolveKategoriByKodeAndName(?string $kodeExcel, ?string $namaKategoriExcel, string $namaPerangkat): ?Kategori
    {
        // 1. Prioritas TERTINGGI: Kode Kategori dari Excel
        if (!empty($kodeExcel)) {
            $cleanKode = $this->normalizeKategoriKode($kodeExcel);
            
            // Cek by Kode di Map Memory
            if ($cleanKode && isset($this->kategoriCodeMap[$cleanKode])) {
                 return Kategori::find($this->kategoriCodeMap[$cleanKode]);
            }
            
            // Cek/Buat berdasarkan Kode
            if ($cleanKode) {
                // Tentukan nama: Prioritas dari kolom Kategori Excel -> fallback Nama Perangkat
                $namaFinal = !empty($namaKategoriExcel) ? trim($namaKategoriExcel) : $namaPerangkat;

                // [FIX] Gunakan updateOrCreate atau firstOrCreate by Kode
                $kategori = Kategori::firstOrCreate(
                    ['kode_kategori' => $cleanKode], // Kunci pencarian (Unique Kode)
                    ['nama_kategori' => $namaFinal]  // Data yg diisi jika baru
                );
                
                $this->updateKategoriCache($kategori);
                return $kategori;
            }
        }

        // 2. Prioritas KEDUA: Nama Kategori dari Excel
        if (!empty($namaKategoriExcel)) {
            $namaFinal = trim($namaKategoriExcel);
            $lookup = mb_strtolower($namaFinal);
            
            if (isset($this->kategoriMap[$lookup])) {
                return Kategori::find($this->kategoriMap[$lookup]);
            }

            // [FIX] Gunakan firstOrCreate berdasarkan Nama
            // Jika kode otomatis diperlukan, kita generate di dalam array value
            $kategori = Kategori::firstOrCreate(
                ['nama_kategori' => $namaFinal], // Kunci pencarian (Unique Nama)
                ['kode_kategori' => $this->nextKategoriKode()] // Generate kode jika baru
            );

            $this->updateKategoriCache($kategori);
            return $kategori;
        }

        // 3. Prioritas TERAKHIR: Fallback (Tebak dari Nama Perangkat)
        // Kita anggap Nama Perangkat = Nama Kategori jika kategori kosong
        return $this->resolveKategoriByNamaPerangkat($namaPerangkat);
    }

    protected function resolveKategoriByNamaPerangkat(string $namaPerangkat): ?Kategori
    {
        $lookup = mb_strtolower(trim($namaPerangkat));
        if (isset($this->kategoriMap[$lookup])) return Kategori::find($this->kategoriMap[$lookup]);
        
        // [FIX] Gunakan firstOrCreate
        $kategori = Kategori::firstOrCreate(
            ['nama_kategori' => trim($namaPerangkat)],
            ['kode_kategori' => $this->nextKategoriKode()]
        );

        $this->updateKategoriCache($kategori);
        return $kategori;
    }

    // Helper untuk update cache setelah create baru
    protected function updateKategoriCache(Kategori $kategori): void
    {
        $this->kategoriMap[mb_strtolower($kategori->nama_kategori)] = (int)$kategori->id;
        if ($kategori->kode_kategori) {
            $key = $this->normalizeKategoriKode($kategori->kode_kategori);
            if ($key) $this->kategoriCodeMap[$key] = (int)$kategori->id;
        }
    }

    protected function normalizeKategoriKode(?string $kode): ?string
    {
        if (!$kode) return null;
        $k = preg_replace('/\D+/', '', (string)$kode);
        return ($k === '') ? null : str_pad(substr($k, -3), 3, '0', STR_PAD_LEFT);
    }

    protected function nextKategoriKode(): string
    {
        // Ambil max kode yang ada untuk auto increment logic
        $max = Kategori::query()
            ->selectRaw('MAX(CAST(kode_kategori AS UNSIGNED)) as max_code')
            ->value('max_code');
            
        $n = (int) $max;
        return str_pad($n + 1, 3, '0', STR_PAD_LEFT);
    }

    protected function resolveOrCreateJenisByName(string $nama): ?Jenis
    {
        $key = mb_strtolower(trim($nama));
        if ($key === '') return null;

        if (isset($this->jenisMap[$key])) {
            return Jenis::find($this->jenisMap[$key]);
        }

        // Mapping Manual
        $rules = [
            'elektronik'             => ['B', '02.4'],
            'kelistrikan'            => ['B', '02.4'],
            'perabotan'              => ['B', '02.6'],
            'meubel'                 => ['B', '02.6'],
            'furniture'              => ['B', '02.6'],
            'alat keselamatan kerja' => ['C', '03.2'],
            'k3'                     => ['C', '03.2'],
            'alat kesehatan'         => ['A', '01'],
            'alkes'                  => ['A', '01'],
        ];

        $prefix = 'B';
        $kode   = '02.4';

        foreach ($rules as $keyword => $config) {
            if (str_contains($key, $keyword)) {
                $prefix = $config[0];
                $kode   = $config[1];
                break;
            }
        }

        // [FIX] Gunakan firstOrCreate
        $jenis = Jenis::firstOrCreate(
            ['nama_jenis' => $nama],
            [
                'prefix'     => $prefix,
                'kode_jenis' => $kode
            ]
        );

        $this->jenisMap[$key] = (int)$jenis->id;
        return $jenis;
    }
    
    protected function parseNomorInventaris(string $ni): ?array
    {
        $re = '/^([A-Z])\.(\d{2}\.\d)\.(\d{3})\.(\d+)\.(\d{4})$/';
        if (!preg_match($re, trim($ni), $m)) return null;

        return [
            'prefix'     => $m[1],
            'kode_jenis' => $m[2],
            'kode_kat'   => $m[3],
            'urut'       => (int)$m[4],
            'tahun'      => (int)$m[5],
        ];
    }

    protected function resolveOrUpsertJenisFromNI(string $prefix, string $kodeJenis): int
    {
        // [FIX] firstOrCreate
        $jenis = Jenis::firstOrCreate(
            ['kode_jenis' => $kodeJenis],
            ['prefix' => $prefix, 'nama_jenis' => 'Hardware']
        );
        return $jenis->id;
    }
}