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
use App\Support\NomorInventarisGenerator; // <--- WAJIB IMPORT INI UNTUK GENERATE NOMOR
use UnitEnum;

class ImportPerangkat extends Page 
{
    use MapsMaster; 

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationLabel = 'Import Perangkat (Preview)';
    protected static ?string $title           = 'Import Perangkat (Preview)';
    protected static ?string $slug            = 'perangkat/import-preview';
    protected static \UnitEnum|string|null $navigationGroup = 'Manajemen Inventaris';

    protected string $view = 'filament.pages.import-perangkat';

    public array $data = [
        'file'       => null,
        'header_row' => 1, // Default baris ke-1
        'policy'     => 'skip',
    ];
    public array $dupes = [];
    public int $totalRows = 0;
    public ?string $scanToken = null;
    public array $headers = [];
    public array $previewRows = [];
    public int $previewLimit = 50;
    protected int $skippedNoName = 0;
    protected int $skippedDupes = 0;

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user instanceof AppUser; // Sesuaikan permission jika perlu
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
                ->description('Unggah Excel, klik Scan untuk melihat duplikat sebelum import.')
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
                    
                    // --- SOLUSI MASALAH 1: Input Posisi Header ---
                    Forms\Components\TextInput::make('header_row')
                        ->label('Posisi Baris Header')
                        ->helperText('Di baris ke berapa nama kolom (Nama Alat, Merk, dll) berada? Ubah jika header tidak terdeteksi.')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),

                    Forms\Components\Radio::make('policy')
                        ->label('Kebijakan saat duplikat')
                        ->options([
                            'skip'      => 'Skip semua duplikat',
                            'overwrite' => 'Overwrite semua duplikat',
                            'selective' => 'Pilih per item (checklist)',
                        ])
                        ->default('skip')
                        ->afterStateUpdated(function ($set, $state) {
                            if ($state !== 'selective') {
                                $set('selective', []);
                            }
                        })
                        ->inline(),

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

            Schemas\Components\Section::make('Preview Duplikat')
                ->description('Nomor inventaris yang sudah ada di database.')
                ->schema([
                    Schemas\Components\View::make('filament.import.preview-summary')
                        ->visible(fn() => $this->scanToken !== null),

                    Forms\Components\CheckboxList::make('selective')
                        ->options(fn() => collect($this->dupes)
                            ->map(fn($n) => strtoupper(trim((string)$n)))
                            ->unique()
                            ->mapWithKeys(fn($n) => [$n => $n])
                            ->all())
                        ->columns(2)
                        ->reactive()
                        ->bulkToggleable(false)
                        ->visible(fn() => $this->scanToken !== null && ($this->data['policy'] ?? 'skip') === 'selective'),

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
                ->visible(fn() => !empty($this->dupes) || $this->scanToken !== null),
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
        
        // Ambil baris header dari input user
        $headerRow = (int)($state['header_row'] ?? 1); 

        // Anonymous Class Dynamic Heading Row
        $collector = new class($this, $headerRow) implements ToCollection, WithHeadingRow {
            public array $rows = [];
            public function __construct(private ImportPerangkat $page, private int $hRow) {}
            
            public function headingRow(): int { return $this->hRow; } // Set header dinamis

            public function collection(Collection $collection)
            {
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

        $allKeys = [];
        foreach ($rows as $r) {
            foreach (array_keys($r) as $k) $allKeys[$k] = true;
        }

        $preferred = [
            'nomor_inventaris', 'nama_perangkat', 'jenis', 'lokasi', 'status', 'kondisi',
            'kategori_excel', 'merek_alat', 'tahun_pengadaan', 'harga_beli', 'keterangan'
        ];
        
        $others = array_values(array_diff(array_keys($allKeys), $preferred));
        $this->headers = array_values(array_unique(array_merge($preferred, $others)));
        $this->previewRows = array_slice($rows, 0, $this->previewLimit);

        $numbers = [];
        foreach ($rows as $r) {
            $n = $this->normalizeNomor($r['nomor_inventaris'] ?? '');
            if ($n) $numbers[] = $n;
        }
        $numbers = array_values(array_unique($numbers));

        $exist = [];
        if (!empty($numbers)) {
            $exist = Perangkat::query()->whereIn('nomor_inventaris', $numbers)->pluck('nomor_inventaris')->all();
        }

        $this->dupes = $exist;
        $this->data['selective'] = [];

        $this->scanToken = (string) Str::uuid();
        cache()->put("import_scan:{$this->scanToken}", [
            'file'       => $state['file'],
            'header_row' => $headerRow, // Simpan info header ke cache
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

        $filePath = Storage::disk('public')->path($scan['file']);
        $policy   = $this->data['policy'] ?? 'skip';
        $headerRow = $scan['header_row'] ?? 1; // Ambil header dari cache

        $collector = new class($this, $headerRow) implements ToCollection, WithHeadingRow {
            public array $rows = [];
            public function __construct(private ImportPerangkat $page, private int $hRow) {}
            public function headingRow(): int { return $this->hRow; }
            public function collection(Collection $collection)
            {
                foreach ($collection as $row) {
                    $raw = array_change_key_case($row->toArray(), CASE_LOWER);
                    $raw = $this->page->normalizeRowKeys($raw);
                    $this->rows[] = $raw;
                }
            }
        };
        
        Excel::import($collector, $filePath);
        $rows = $collector->rows;

        $allowOverwrite = [];
        if ($policy === 'selective') {
            $allowOverwrite = collect($this->data['selective'] ?? [])->map(fn($n)=>strtoupper(trim((string)$n)))->all();
        }

        $inserted = 0; $updated = 0;
        $this->skippedNoName = 0; $this->skippedDupes = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $nama = trim((string) ($row['nama_perangkat'] ?? ''));
                if ($nama === '') { $this->skippedNoName++; continue; }

                $nomor = $this->normalizeNomor($row['nomor_inventaris'] ?? null);
                
                // Resolve Relasi
                $lokasi_id  = $this->getOrCreateId($this->lokasiMap,  Lokasi::class,  'nama_lokasi',  $row['lokasi'] ?? null);
                // Cek apakah kolom status ada isinya. Jika kosong string atau null, biarkan null.
                // Jika Anda ingin Default 'Baik', ganti null di paling belakang dengan 'Baik'
                $inputStatus = !empty($row['status']) ? $row['status'] : null; 
                $status_id  = $this->getOrCreateId($this->statusMap,  Status::class,  'nama_status', $inputStatus);

                // Kondisi juga sama, mau default 'Baik' atau null?
                $inputKondisi = !empty($row['kondisi']) ? $row['kondisi'] : null; // Contoh ini tetap default Baik
                $kondisi_id = $this->getOrCreateId($this->kondisiMap, Kondisi::class, 'nama_kondisi', $inputKondisi);

                $tahun = !empty($row['tahun_pengadaan']) ? (int)$row['tahun_pengadaan'] : (int) now()->year;
                $harga = !empty($row['harga_beli']) ? (int)preg_replace('/\D+/', '', (string)$row['harga_beli']) : 0;
                $tglPengadaan = $this->parseTanggal($row['tanggal_pengadaan'] ?? null);
                
                // Ambil data dari kolom Excel (pastikan mapping alias di MapsMaster sudah benar: 'kategori' => 'kategori_excel')
                $excelKodeKat = $row['kode_kategori_excel'] ?? null;
                $excelNamaKat = $row['kategori_excel'] ?? null; // <--- Ambil Nama Kategori Excel

                // Panggil method dengan 3 parameter (Kode, Nama Kategori Excel, Nama Perangkat)
                $kategoriObj  = $this->resolveKategoriByKodeAndName($excelKodeKat, $excelNamaKat, $nama);
                $kategori_id  = $kategoriObj ? $kategoriObj->id : null;

                // Resolve Jenis menggunakan Logic Baru di MapsMaster
                $jenisObj = $this->resolveOrCreateJenisByName($row['jenis'] ?? 'Elektronik'); 
                $jenis_id = $jenisObj ? $jenisObj->id : null;

                // --- SOLUSI MASALAH 2: Auto Generate Nomor Inventaris ---
                if ($nomor === null) {
                    // Jika di excel kosong, generate otomatis
                    // Pastikan Generator menerima (jenis_id, kategori_id, tahun)
                    $nomor = NomorInventarisGenerator::generate($jenis_id, $kategori_id, $tahun);
                }

                // Cek Duplikat di DB (Update vs Insert)
                $existing = Perangkat::where('nomor_inventaris', $nomor)->first();
                if ($existing) {
                    $shouldUpdate = ($policy === 'overwrite') || ($policy === 'selective' && in_array($nomor, $allowOverwrite));
                    if ($shouldUpdate) {
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
                        $updated++;
                    } else {
                        $this->skippedDupes++;
                    }
                } else {
                    // Insert Baru
                    Perangkat::create([
                        'nama_perangkat'    => $nama,
                        'nomor_inventaris'  => $nomor, // Gunakan nomor (baik dari excel atau hasil generate)
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
                    $inserted++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->title('Error Import')->body($e->getMessage())->danger()->send();
            return;
        }

        $msg = "Insert: {$inserted}, Update: {$updated}";
        if ($this->skippedNoName > 0 || $this->skippedDupes > 0) {
            $msg .= " | Skip(Nama Kosong): {$this->skippedNoName}, Skip(Duplikat): {$this->skippedDupes}";
        }

        Notification::make()->title('Import selesai')->body($msg)->success()->send();

        // Reset
        $this->data = ['file' => null, 'header_row' => 1, 'policy' => 'skip', 'selective' => []];
        $this->dupes = []; $this->totalRows = 0; $this->scanToken = null;
        $this->form->fill($this->data);
    }
}