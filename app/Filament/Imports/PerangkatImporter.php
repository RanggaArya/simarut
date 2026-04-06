<?php

namespace App\Filament\Imports;

use App\Models\Perangkat;
use App\Models\Lokasi;
use App\Models\Status;
use App\Models\Kondisi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use App\Filament\Imports\Traits\MapsMaster;

class PerangkatImporter implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use Importable;
    use MapsMaster;

    private array $seenNomorInventaris = [];
    private array $nextUrutMap = [];

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

    public function onFailure(Failure ...$failures) {}

    public function collection(Collection $rows)
    {
        // 1. Normalisasi Header & Data
        $rows = $rows->values()->map(function ($row, $i) {
            $r = $this->normalizeRowKeys((array) $row);
            $r['__row_index']    = $i;
            $r['nama_perangkat'] = trim((string)($r['nama_perangkat'] ?? ''));
            return $r;
        })->filter(fn($r) => $r['nama_perangkat'] !== '');

        $withNI = [];
        $toGen  = [];

        // 2. Pisahkan Data (Punya No Inventaris vs Belum)
        foreach ($rows as $row) {
            $nomor = $this->normalizeNomor($row['nomor_inventaris'] ?? null);

            if ($nomor) {
                if (isset($this->seenNomorInventaris[$nomor])) continue;
                if (Perangkat::where('nomor_inventaris', $nomor)->exists()) {
                    $this->seenNomorInventaris[$nomor] = true;
                    continue;
                }
                $this->seenNomorInventaris[$nomor] = true;
                $withNI[] = [$row, $nomor];
            } else {
                $toGen[] = $row;
            }
        }

        // 3. Simpan data yang sudah punya Nomor (Migrasi)
        foreach ($withNI as [$row, $nomor]) {
            $this->persistRow($row, $nomor);
        }

        if (empty($toGen)) return;

        // 4. Siapkan Data Baru (Generate Nomor)
        $prepared = collect($toGen)->map(function ($row) {
            $namaPerangkat = $row['nama_perangkat'];

            // Cek Kode Kategori dari Excel (Priority)
            $kodeKategoriExcel = $row['kode_kategori_excel'] ?? null;
            $namaKategoriExcel = $row['kategori_excel'] ?? null; // Ambil kolom Kategori
            
            // Panggil method resolver dari MapsMaster (3 parameter)
            $kategori = $this->resolveKategoriByKodeAndName($kodeKategoriExcel, $namaKategoriExcel, $namaPerangkat);

            $jenis = $this->resolveOrCreateJenisByName($row['jenis'] ?? 'Hardware');
            $tahun = !empty($row['tahun_pengadaan']) ? (int)$row['tahun_pengadaan'] : (int) now()->year;

            if (!$kategori || !$jenis) return null;

            return array_merge($row, [
                '_prefix'   => strtoupper($jenis->prefix ?: 'B'),
                '_kodeJ'    => $jenis->kode_jenis ?: '02.4',
                '_kodeK'    => str_pad(preg_replace('/\D+/', '', (string) $kategori->kode_kategori), 3, '0', STR_PAD_LEFT),
                '_jenis_id' => $jenis->id,
                '_kat_id'   => $kategori->id,
                '_tahun'    => $tahun,
                '_nama_key' => mb_strtolower($this->normalizeDeviceName($namaPerangkat)),
            ]);
        })->filter()->values();

        if ($prepared->isEmpty()) return;

        // 5. Generate Nomor Batch
        DB::transaction(function () use ($prepared) {
            $prepared->groupBy(fn($r) => "{$r['_prefix']}|{$r['_kodeJ']}|{$r['_kodeK']}")
                ->each(function (Collection $group, string $key) {

                    // Cek urutan terakhir di DB
                    if (!isset($this->nextUrutMap[$key])) {
                        [$p, $kj, $kk] = explode('|', $key);
                        $like = "{$p}.{$kj}.{$kk}.%";
                        $maxUrut = (int) DB::table('perangkats')
                            ->where('nomor_inventaris', 'like', $like)
                            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_inventaris, '.', -2), '.', 1) AS UNSIGNED)) as max_urut")
                            ->value('max_urut');
                        $this->nextUrutMap[$key] = $maxUrut;
                    }

                    $sorted = $group->sortBy([
                        ['_nama_key', 'asc'],
                        ['_tahun', 'asc'],
                        ['__row_index', 'asc'],
                    ])->values();

                    foreach ($sorted as $row) {
                        $urut = ++$this->nextUrutMap[$key];
                        $width = max(3, strlen((string) $urut)); 
                        $urutStr = str_pad((string) $urut, $width, '0', STR_PAD_LEFT);

                        $nomor = "{$row['_prefix']}.{$row['_kodeJ']}.{$row['_kodeK']}.{$urutStr}.{$row['_tahun']}";

                        // Cek collision
                        while (
                            isset($this->seenNomorInventaris[$nomor]) ||
                            DB::table('perangkats')->where('nomor_inventaris', $nomor)->exists()
                        ) {
                            $urut = ++$this->nextUrutMap[$key];
                            $width = max(3, strlen((string) $urut));
                            $urutStr = str_pad((string) $urut, $width, '0', STR_PAD_LEFT);
                            $nomor = "{$row['_prefix']}.{$row['_kodeJ']}.{$row['_kodeK']}.{$urutStr}.{$row['_tahun']}";
                        }

                        $this->seenNomorInventaris[$nomor] = true;
                        $this->persistRow($row, $nomor);
                    }
                });
        });
    }

    private function persistRow(array $row, string $nomor): void
    {
        // Foreign Keys
        $lokasi_id  = $this->getOrCreateId($this->lokasiMap,  Lokasi::class,  'nama_lokasi',  $row['lokasi'] ?? null);
        // Cek apakah kolom status ada isinya. Jika kosong string atau null, biarkan null.
        // Jika Anda ingin Default 'Baik', ganti null di paling belakang dengan 'Baik'
        $inputStatus = !empty($row['status']) ? $row['status'] : null; 

        $status_id  = $this->getOrCreateId($this->statusMap,  Status::class,  'nama_status', $inputStatus);

        // Kondisi juga sama, mau default 'Baik' atau null?
        $inputKondisi = !empty($row['kondisi']) ? $row['kondisi'] : null; // Contoh ini tetap default Baik
        $kondisi_id = $this->getOrCreateId($this->kondisiMap, Kondisi::class, 'nama_kondisi', $inputKondisi);

        // Cleaning Data
        $harga = !empty($row['harga_beli']) ? (int)preg_replace('/\D+/', '', (string)$row['harga_beli']) : 0;
        $tglPengadaan = $this->parseTanggal($row['tanggal_pengadaan'] ?? null);
        
        // Resolve IDs
        $tahun       = (int)($row['_tahun'] ?? ($row['tahun_pengadaan'] ?? now()->year));
        $jenis_id    = (int)($row['_jenis_id'] ?? optional($this->resolveOrCreateJenisByName($row['jenis'] ?? 'Hardware'))->id);
        
        $namaPerangkat = $row['nama_perangkat'];
        // Kategori
        $kodeKategoriExcel = $row['kode_kategori_excel'] ?? null;
        $namaKategoriExcel = $row['kategori_excel'] ?? null;
        $kategoriObj = $this->resolveKategoriByKodeAndName($kodeKategoriExcel, $namaKategoriExcel, $namaPerangkat);
        $kategori_id = $kategoriObj ? $kategoriObj->id : null;

        // CREATE (Sesuai $fillable Model yang Baru)
        Perangkat::create([
            'lokasi_id'         => $lokasi_id,
            'nomor_inventaris'  => $nomor,
            'kategori_id'       => $kategori_id,
            'jenis_id'          => $jenis_id,
            'nama_perangkat'    => $row['nama_perangkat'],
            'merek_alat'        => $row['merek_alat'] ?? null,
            'kondisi_id'        => $kondisi_id,
            'tanggal_pengadaan' => $tglPengadaan,
            'tanggal_supervisi' => null, 
            'tahun_pengadaan'   => $tahun,
            'sumber_pendanaan'  => $row['sumber_pendanaan'] ?? null,
            'harga_beli'        => $harga,
            'keterangan'        => $row['keterangan'] ?? null,
            'status_id'         => $status_id,
            // 'created_by'     => auth()->id(), 
        ]);
    }
}