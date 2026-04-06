<?php

namespace App\Filament\Pages;

use App\Models\Perangkat;
use App\Models\Lokasi;
use App\Models\Status;
use App\Models\Kondisi;
use Filament\Forms; 
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Actions\Action; 
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Models\User as AppUser;
use Illuminate\Support\Facades\DB;
use App\Filament\Imports\Traits\MapsMaster;
use App\Support\NomorInventarisGenerator; // PENTING: Class Generator
use UnitEnum;

class ImportPerangkat extends Page 
{
    use MapsMaster; 

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Import Perangkat (Preview)';
    protected static ?string $title           = 'Import Perangkat (Preview)';
    protected static ?string $slug            = 'perangkat/import-preview';
    protected static \UnitEnum|string|null $navigationGroup = 'Manajemen Perangkat';

    protected string $view = 'filament.pages.import-perangkat';

    public array $data = [
        'file'       => null,
        'header_row' => 1, 
        'policy'     => 'skip',
    ];
    
    public array $dupes = [];
    public int $totalRows = 0;
    public ?string $scanToken = null;
    public array $headers = [];
    public array $previewRows = [];
    public int $previewLimit = 50;
    
    // Statistik Import
    protected int $insertedCount = 0;
    protected int $updatedCount = 0;
    protected int $skippedNoName = 0;
    protected int $skippedDupes = 0;

    public static function canAccess(): bool
    {
        $user = Auth::user();
        
        // Cek apakah user valid DAN memiliki izin 'perangkat.import'
        // Izin ini sudah ada di daftar 'super-admin' dan 'admin' pada file User.php
        return $user instanceof AppUser && $user->canDo('perangkat.import');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function mount(): void
    {
        $this->bootMasterMaps();
        $this->form->fill($this->data); 
    }

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Schemas\Components\Section::make('Upload File')
                ->description('Unggah Excel, atur baris header, dan pilih kebijakan duplikat.')
                ->schema([
                    Forms\Components\FileUpload::make('file')
                        ->label('File Excel')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->directory('imports')
                        ->disk('public')
                        ->required()
                        ->columnSpan(2),
                    
                    Forms\Components\TextInput::make('header_row')
                        ->label('Posisi Baris Header')
                        ->helperText('Baris ke berapa judul kolom berada? (Default: 1)')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),

                    // --- OPSI YANG DISEDERHANAKAN (HANYA SKIP & OVERWRITE) ---
                    Forms\Components\Radio::make('policy')
                        ->label('Kebijakan Duplikat (Berdasarkan Nomor Inventaris)')
                        ->options([
                            'skip'      => 'Skip (Lewati data jika no. inventaris sudah ada)',
                            'overwrite' => 'Overwrite (Timpa/Update data jika no. inventaris sudah ada)',
                        ])
                        ->default('skip')
                        ->inline()
                        ->columnSpan(2),

                    Schemas\Components\Actions::make([
                        Action::make('scan')
                            ->label('Scan File')
                            ->action('scanFile')
                            ->color('primary')
                            ->icon('heroicon-o-magnifying-glass'),
                    ])->alignLeft(),
                ])->columns(2),

            Schemas\Components\Section::make('Preview Isi File')
                ->description('Cuplikan isi file setelah dinormalisasi header.')
                ->schema([
                    Schemas\Components\View::make('filament.import.preview-table')
                        ->visible(fn() => $this->scanToken !== null),
                ])
                ->visible(fn() => $this->scanToken !== null),

            Schemas\Components\Section::make('Ringkasan Scan')
                ->description('Cek duplikat dan jalankan import.')
                ->schema([
                    Schemas\Components\View::make('filament.import.preview-summary')
                        ->visible(fn() => $this->scanToken !== null),

                    // Tombol Run Import
                    Schemas\Components\Actions::make([
                        Action::make('run')
                            ->label('Jalankan Import')
                            ->action('runImport')
                            ->color('success')
                            ->icon('heroicon-o-play')
                            ->requiresConfirmation()
                            ->visible(fn() => $this->scanToken !== null),
                    ])->alignLeft(),
                ])
                ->visible(fn() => $this->scanToken !== null),
        ])->statePath('data');
    }

    public function scanFile(): void
    {
        $state = $this->form->getState();
        if (empty($state['file'])) {
            Notification::make()->title('File belum dipilih')->danger()->send();
            return;
        }

        $this->data['policy'] = $state['policy'] ?? 'skip';
        $fullPath = Storage::disk('public')->path($state['file']);
        $headerRow = (int)($state['header_row'] ?? 1); 

        // Anonymous Class untuk Membaca Excel
        $collector = new class($this, $headerRow) implements ToCollection, WithHeadingRow {
            public array $rows = [];
            public function __construct(private ImportPerangkat $page, private int $hRow) {}
            public function headingRow(): int { return $this->hRow; } 
            public function collection(Collection $collection) {
                foreach ($collection as $row) {
                    $raw = array_change_key_case($row->toArray(), CASE_LOWER);
                    $raw = $this->page->normalizeRowKeys($raw);
                    $this->rows[] = $raw;
                }
            }
        };

        try {
            Excel::import($collector, $fullPath);
        } catch (\Exception $e) {
            Notification::make()->title('Gagal membaca file')->body($e->getMessage())->danger()->send();
            return;
        }

        $rows = $collector->rows;
        $this->totalRows = count($rows);

        // Ambil Headers untuk Preview Table
        $allKeys = [];
        foreach ($rows as $r) {
            foreach (array_keys($r) as $k) $allKeys[$k] = true;
        }
        $preferred = ['nomor_inventaris', 'nama_perangkat', 'jenis', 'lokasi', 'status', 'merek_alat', 'tahun_pengadaan', 'kategori_excel'];
        $others = array_values(array_diff(array_keys($allKeys), $preferred));
        $this->headers = array_values(array_unique(array_merge($preferred, $others)));
        $this->previewRows = array_slice($rows, 0, $this->previewLimit);

        // --- CEK DUPLIKAT (Database & Internal File) ---
        $numbersFound = [];
        $internalDupes = [];
        
        foreach ($rows as $r) {
            $n = $this->normalizeNomor($r['nomor_inventaris'] ?? '');
            if ($n) {
                // Cek duplikat sesama baris di Excel
                if (in_array($n, $numbersFound)) {
                    $internalDupes[] = $n;
                }
                $numbersFound[] = $n;
            }
        }
        
        $dbDupes = [];
        if (!empty($numbersFound)) {
            $dbDupes = Perangkat::query()
                ->whereIn('nomor_inventaris', array_unique($numbersFound))
                ->pluck('nomor_inventaris')
                ->all();
        }

        // Gabungkan duplikat DB dan Internal
        $this->dupes = array_unique(array_merge($dbDupes, $internalDupes));

        $this->scanToken = (string) Str::uuid();
        cache()->put("import_scan:{$this->scanToken}", [
            'file'       => $state['file'],
            'header_row' => $headerRow,
            'policy'     => $this->data['policy'],
            'dupes'      => $this->dupes,
            'total'      => $this->totalRows,
        ], now()->addMinutes(30));

        Notification::make()->title('Scan selesai')->success()->send();
    }

    public function runImport(): void
    {
        if (!$this->scanToken) {
            Notification::make()->title('Belum scan')->danger()->send(); return;
        }
        $scan = cache()->pull("import_scan:{$this->scanToken}");
        if (!$scan) {
            Notification::make()->title('Sesi habis')->danger()->send(); return;
        }

        $filePath  = Storage::disk('public')->path($scan['file']);
        $policy    = $this->data['policy'] ?? 'skip';
        $headerRow = $scan['header_row'] ?? 1;

        // Baca Ulang File untuk Proses Import
        $collector = new class($this, $headerRow) implements ToCollection, WithHeadingRow {
            public array $rows = [];
            public function __construct(private ImportPerangkat $page, private int $hRow) {}
            public function headingRow(): int { return $this->hRow; }
            public function collection(Collection $collection) {
                foreach ($collection as $row) {
                    $raw = array_change_key_case($row->toArray(), CASE_LOWER);
                    $raw = $this->page->normalizeRowKeys($raw);
                    $this->rows[] = $raw;
                }
            }
        };
        
        try {
            Excel::import($collector, $filePath);
        } catch (\Exception $e) {
            Notification::make()->title('Gagal membaca file')->body($e->getMessage())->danger()->send();
            return;
        }

        $rows = $collector->rows;
        $this->resetCounters();

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $nama = trim((string) ($row['nama_perangkat'] ?? ''));
                if ($nama === '') { 
                    $this->skippedNoName++; 
                    continue; 
                }

                // 1. Ambil / Generate Nomor Inventaris
                $nomor = $this->normalizeNomor($row['nomor_inventaris'] ?? null);
                
                // 2. Resolve Kategori (Prioritas: Kode -> Nama Excel -> Nama Perangkat)
                $excelKodeKat = $row['kode_kategori_excel'] ?? null;
                $excelNamaKat = $row['kategori_excel'] ?? null;
                $kategoriObj  = $this->resolveKategoriByKodeAndName($excelKodeKat, $excelNamaKat, $nama);
                $kategori_id  = $kategoriObj ? $kategoriObj->id : null;

                // 3. Resolve Jenis (Elektronik=B, dsb)
                $jenisObj = $this->resolveOrCreateJenisByName($row['jenis'] ?? 'Hardware'); 
                $jenis_id = $jenisObj ? $jenisObj->id : null;

                $tahun = !empty($row['tahun_pengadaan']) ? (int)$row['tahun_pengadaan'] : (int) now()->year;

                // --- LOGIC AUTO GENERATE ---
                if ($nomor === null) {
                    // Jika di Excel kosong, generate nomor baru
                    $nomor = NomorInventarisGenerator::generate($jenis_id, $kategori_id, $tahun);
                }

                // 4. Resolve Data Lain
                $lokasi_id = $this->getOrCreateId($this->lokasiMap, Lokasi::class, 'nama_lokasi', $row['lokasi'] ?? null);
                $status_id = $this->getOrCreateId($this->statusMap, Status::class, 'nama_status', $row['status'] ?? null); // Null jika kosong (tdk default 'Baik')
                
                $inputKondisi = !empty($row['kondisi']) ? $row['kondisi'] : 'Baik'; // Default Baik jika kosong
                $kondisi_id   = $this->getOrCreateId($this->kondisiMap, Kondisi::class, 'nama_kondisi', $inputKondisi);

                $harga        = !empty($row['harga_beli']) ? (int)preg_replace('/\D+/', '', (string)$row['harga_beli']) : 0;
                $tglPengadaan = $this->parseTanggal($row['tanggal_pengadaan'] ?? null);

                // --- LOGIC DUPLICATE CHECK (SKIP vs OVERWRITE) ---
                $existing = Perangkat::where('nomor_inventaris', $nomor)->first();

                if ($existing) {
                    // Data sudah ada di DB
                    if ($policy === 'skip') {
                        // Kebijakan SKIP: Jangan lakukan apa-apa
                        $this->skippedDupes++;
                    } else {
                        // Kebijakan OVERWRITE: Timpa data
                        $existing->update([
                            'nama_perangkat'    => $nama,
                            'merek_alat'        => $row['merek_alat'] ?? null,
                            'sumber_pendanaan'  => $row['sumber_pendanaan'] ?? null,
                            'tahun_pengadaan'   => $tahun,
                            'harga_beli'        => $harga,
                            'keterangan'        => $row['keterangan'] ?? null,
                            'tanggal_pengadaan' => $tglPengadaan,
                            'lokasi_id'         => $lokasi_id,
                            'jenis_id'          => $jenis_id,
                            'status_id'         => $status_id,
                            'kondisi_id'        => $kondisi_id,
                            'kategori_id'       => $kategori_id ?? $existing->kategori_id,
                        ]);
                        $this->updatedCount++;
                    }
                } else {
                    // Data Belum Ada -> INSERT BARU
                    Perangkat::create([
                        'nama_perangkat'    => $nama,
                        'nomor_inventaris'  => $nomor,
                        'merek_alat'        => $row['merek_alat'] ?? null,
                        'sumber_pendanaan'  => $row['sumber_pendanaan'] ?? null,
                        'tahun_pengadaan'   => $tahun,
                        'harga_beli'        => $harga,
                        'keterangan'        => $row['keterangan'] ?? null,
                        'tanggal_pengadaan' => $tglPengadaan,
                        'lokasi_id'         => $lokasi_id,
                        'jenis_id'          => $jenis_id,
                        'status_id'         => $status_id,
                        'kondisi_id'        => $kondisi_id,
                        'kategori_id'       => $kategori_id,
                    ]);
                    $this->insertedCount++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->title('Error Import')->body($e->getMessage())->danger()->send();
            return;
        }

        $msg = "Masuk: {$this->insertedCount}, Update: {$this->updatedCount}";
        if ($this->skippedNoName > 0 || $this->skippedDupes > 0) {
            $msg .= " | Skip(Tanpa Nama): {$this->skippedNoName}, Skip(Duplikat): {$this->skippedDupes}";
        }

        Notification::make()->title('Import Selesai')->body($msg)->success()->send();

        // Reset Form
        $this->data = ['file' => null, 'header_row' => 1, 'policy' => 'skip'];
        $this->dupes = []; 
        $this->totalRows = 0; 
        $this->scanToken = null;
        $this->form->fill($this->data);
    }

    private function resetCounters(): void
    {
        $this->insertedCount = 0;
        $this->updatedCount = 0;
        $this->skippedNoName = 0;
        $this->skippedDupes = 0;
    }
}