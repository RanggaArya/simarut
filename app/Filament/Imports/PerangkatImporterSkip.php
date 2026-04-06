<?php

namespace App\Filament\Imports;

use App\Models\Perangkat;
use App\Models\Kategori; // Pastikan Model Kategori dipanggil
use App\Filament\Imports\Traits\MapsMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use App\Support\NomorInventarisGenerator;

class PerangkatImporterSkip implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure
{
    use \Maatwebsite\Excel\Concerns\Importable;
    use MapsMaster;

    public function __construct()
    {
        $this->bootMasterMaps();
    }

    public function rules(): array
    {
        return [
            'nomor_inventaris' => 'nullable|unique:perangkats,nomor_inventaris',
        ];
    }

    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures) {}

    public function model(array $row)
    {
        $row = $this->normalizeRowKeys($row);
        $namaPerangkat = trim((string)($row['nama_perangkat'] ?? ''));
        if ($namaPerangkat === '') return null;

        $nomor = $this->normalizeNomor($row['nomor_inventaris'] ?? null);

        $lokasi_id  = $this->getOrCreateId($this->lokasiMap,  \App\Models\Lokasi::class,  'nama_lokasi',  $row['lokasi'] ?? null);
        $status_id  = $this->getOrCreateId($this->statusMap,  \App\Models\Status::class,  'nama_status',  $row['status'] ?? null);
        $kondisi_id = $this->getOrCreateId($this->kondisiMap, \App\Models\Kondisi::class, 'nama_kondisi', $row['kondisi'] ?? null);

        // --- PERBAIKAN LOGIKA HARGA ---
        $hargaRaw = !empty($row['harga_beli']) ? $row['harga_beli'] : ($row['harga'] ?? null);
        $harga = !empty($hargaRaw) ? (int)preg_replace('/\D+/', '', (string)$hargaRaw) : 0;
        
        $harga_total = !empty($row['harga_total']) ? (int)preg_replace('/\D+/', '', (string)$row['harga_total']) : $harga;

        $tanggalPengadaan = $this->parseTanggal($row['tanggal_pengadaan'] ?? null);
        $tglSupervisi = $this->parseTanggal($row['tanggal_supervisi'] ?? null);
        $kode = isset($row['kode']) ? (trim((string)$row['kode']) ?: null) : null;

        $jenis_id = null;
        $kategori_id = null;
        $tahun = (int) now()->year;

        if ($nomor && ($parts = $this->parseNomorInventaris($nomor))) {
            // PERBAIKAN: Gunakan fungsi yang benar dari MapsMaster
            $jenis_id    = $this->resolveOrUpsertJenisFromNI($parts['prefix'], $parts['kode_jenis']);
            
            $katObj      = $this->resolveKategoriByKodeAndName($parts['kode_kat'], null, $namaPerangkat);
            $kategori_id = $katObj ? $katObj->id : null;
            
            $tahun       = $parts['tahun'];
        } else {
            $jenis_model = $this->resolveOrCreateJenisByName($row['jenis'] ?? 'Hardware');
            $kategori    = $this->resolveKategoriByNamaPerangkat($namaPerangkat);
            $tahun       = !empty($row['tahun_pengadaan']) ? (int)$row['tahun_pengadaan'] : (int)now()->year;

            if (!$jenis_model || !$kategori) return null;

            $jenis_id    = (int)$jenis_model->id;
            $kategori_id = (int)$kategori->id;

            $nomor = NomorInventarisGenerator::generate($jenis_id, $kategori_id, $tahun);
        }

        // --- PERBAIKAN LOGIKA MASA PAKAI ---
        $kategoriObj = Kategori::find($kategori_id);
        $masa_pakai_excel = !empty($row['masa_pakai_bulan']) ? $row['masa_pakai_bulan'] : null;
        $masa_pakai_final = !empty($masa_pakai_excel) ? (int)$masa_pakai_excel : ($kategoriObj->masa_pakai_bulan ?? null);

        return new Perangkat([
            'nama_perangkat'     => $namaPerangkat,
            'tipe'               => $row['tipe'] ?? null,
            'spesifikasi'        => $row['spesifikasi'] ?? null,
            'deskripsi'          => $row['deskripsi'] ?? null,
            'perolehan'          => $row['perolehan'] ?? null,
            'tahun_pengadaan'    => $tahun,
            'nomor_inventaris'   => $nomor,
            'harga_beli'         => $harga,
            'harga_total'        => $harga_total,
            'masa_pakai_bulan'   => $masa_pakai_final,

            'catatan'            => $row['catatan'] ?? null,
            'mutasi'             => $row['mutasi'] ?? null,
            'upgrade'            => $row['upgrade'] ?? null,
            'tanggal_pengadaan'  => $tanggalPengadaan,
            'tanggal_supervisi'  => $tglSupervisi,
            'kode'               => $kode,
            'lokasi_id'          => $lokasi_id,
            'jenis_id'           => $jenis_id,
            'status_id'          => $status_id,
            'kondisi_id'         => $kondisi_id,
            'kategori_id'        => $kategori_id,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}