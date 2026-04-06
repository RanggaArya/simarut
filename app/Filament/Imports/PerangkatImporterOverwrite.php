<?php

namespace App\Filament\Imports;

use App\Models\Perangkat;
use App\Models\JenisPerangkat;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Status;
use App\Models\Kondisi;
use App\Filament\Imports\Traits\MapsMaster;
use App\Support\NomorInventarisGenerator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class PerangkatImporterOverwrite implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure,
    WithUpserts
{
    use Importable;
    use MapsMaster;

    private array $seenNomorInventaris = [];

    public function __construct()
    {
        $this->bootMasterMaps();
    }

    public function rules(): array
    {
        return [
            'nomor_inventaris' => 'nullable|distinct',
        ];
    }

    public function uniqueBy()
    {
        return 'nomor_inventaris';
    }

    public function onFailure(Failure ...$failures)
    {
    }

    public function model(array $row)
    {
        $row = $this->normalizeRowKeys($row);
        $namaPerangkat = trim((string)($row['nama_perangkat'] ?? ''));
        if ($namaPerangkat === '') {
            return null;
        }

        $nomor = $this->normalizeNomor($row['nomor_inventaris'] ?? null);

        if ($nomor !== null) {
            if (isset($this->seenNomorInventaris[$nomor])) {
                return null;
            }
        }

        $lokasi_id  = $this->getOrCreateId($this->lokasiMap,  Lokasi::class,  'nama_lokasi',  $row['lokasi'] ?? null);
        $status_id  = $this->getOrCreateId($this->statusMap,  Status::class,  'nama_status',  $row['status'] ?? null);
        $kondisi_id = $this->getOrCreateId($this->kondisiMap, Kondisi::class, 'nama_kondisi', $row['kondisi'] ?? null);

        // --- PERBAIKAN LOGIKA HARGA ---
        $hargaRaw = !empty($row['harga_beli']) ? $row['harga_beli'] : ($row['harga'] ?? null);
        $harga = !empty($hargaRaw) ? (int)preg_replace('/\D+/', '', (string)$hargaRaw) : 0;
        
        $harga_total = !empty($row['harga_total']) ? (int)preg_replace('/\D+/', '', (string)$row['harga_total']) : $harga;

        $tanggalPengadaan = $this->parseTanggal($row['tanggal_pengadaan'] ?? null);
        $tglSupervisi = $this->parseTanggal($row['tanggal_supervisi'] ?? null);
        $kode = isset($row['kode']) ? (trim((string)$row['kode']) ?: null) : null;

        $jenis_id = null;
        $kategori_id = null;
        $tahun = (int) (now()->year);

        if ($nomor && ($parts = $this->parseNomorInventaris($nomor))) {
            // PERBAIKAN: Gunakan fungsi yang benar dari MapsMaster
            $jenis_id    = $this->resolveOrUpsertJenisFromNI($parts['prefix'], $parts['kode_jenis']);
            
            $katObj      = $this->resolveKategoriByKodeAndName($parts['kode_kat'], null, $namaPerangkat);
            $kategori_id = $katObj ? $katObj->id : null;
            
            $tahun       = $parts['tahun'];
        } else {
            $kategori_model = $this->resolveKategoriByNamaPerangkat($namaPerangkat);
            $jenis_model    = $this->resolveOrCreateJenisByName($row['jenis'] ?? 'Hardware');
            $tahun = !empty($row['tahun_pengadaan']) ? (int)$row['tahun_pengadaan'] : (int)now()->year;

            if (!$jenis_model || !$kategori_model) {
                return null; 
            }

            $jenis_id = $jenis_model->id;
            $kategori_id = $kategori_model->id;

            $nomor = NomorInventarisGenerator::generateFromCodes(
                $jenis_id,
                $jenis_model->prefix,
                $jenis_model->kode_jenis,
                $kategori_id,
                $kategori_model->kode_kategori,
                $tahun
            );
        }

        if ($nomor) {
            if (isset($this->seenNomorInventaris[$nomor])) {
                return null; 
            }
            $this->seenNomorInventaris[$nomor] = true;
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
            
            // --- KOLOM YG DIPERBAIKI ---
            'harga_beli'         => $harga, // Tadinya salah tulis jadi 'harga'
            'harga_total'        => $harga_total,
            'masa_pakai_bulan'   => $masa_pakai_final,

            'catatan'            => $row['catatan'] ?? null,
            'mutasi'             => $row['mutasi'] ?? null,
            'upgrade'            => $row['upgrade'] ?? null,
            'tanggal_pengadaan' => $tanggalPengadaan,
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