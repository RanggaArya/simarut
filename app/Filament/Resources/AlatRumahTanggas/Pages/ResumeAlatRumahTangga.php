<?php

namespace App\Filament\Resources\AlatRumahTanggas\Pages;

use App\Filament\Resources\AlatRumahTanggas\AlatRumahTanggaResource;
use App\Models\Perangkat;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema; 
use Filament\Forms\Components\Select; 
use Filament\Forms\Components\CheckboxList; 

// --- TAMBAHAN IMPORT ---
use Filament\Actions\Action; // Untuk Tombol Header
use Maatwebsite\Excel\Facades\Excel; // Untuk Fungsi Download
use App\Filament\Exports\ResumePerangkatExport; // Class Export kita
use Illuminate\Support\Carbon; // Untuk nama file timestamp

class ResumeAlatRumahTangga extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AlatRumahTanggaResource::class;

    protected string $view = 'filament.resources.alat-rumah-tanggas.pages.resume-alat-rumah-tangga';

    protected static ?string $title = 'Resume Perangkat';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'filter_kondisi' => 'all',
            'filter_nama'    => [], 
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('filter_kondisi')
                    ->options([
                        'all'   => 'All',
                        'baik'  => 'Baik',
                        'rusak' => 'Rusak',
                    ])
                    ->default('all'),
                
                CheckboxList::make('filter_nama')
                    ->default([]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray') // Icon Download
                ->color('success') // Warna Hijau Excel
                ->action(function () {
                    // Nama file pakai timestamp agar unik
                    $fileName = 'Resume_Perangkat_' . Carbon::now()->format('Ymd_His') . '.xlsx';
                    
                    // Kirim $this->data (isi filter saat ini) ke Class Export
                    return Excel::download(new ResumePerangkatExport($this->data), $fileName);
                }),
        ];
    }

    protected function getViewData(): array
    {
        $filterKondisi = $this->data['filter_kondisi'] ?? 'all';
        $filterNama    = $this->data['filter_nama'] ?? [];

        // 1. AMBIL LIST SEMUA NAMA UNIK
        $listNamaAlat = Perangkat::distinct()
            ->orderBy('nama_perangkat', 'asc')
            ->pluck('nama_perangkat')
            ->toArray();

        // 2. QUERY UTAMA
        $query = Perangkat::with(['kondisi'])
            ->orderBy('nama_perangkat', 'asc');

        if (!empty($filterNama)) {
            $query->whereIn('nama_perangkat', $filterNama);
        }

        $allPerangkats = $query->get();

        // 3. GROUPING
        $groupedData = $allPerangkats->groupBy('nama_perangkat');

        // 4. MAPPING DATA (DENGAN PERBAIKAN LOGIKA TOTAL)
        $data = $groupedData->map(function ($items, $namaAlat) use ($filterKondisi) {
            
            // Hitung masing-masing kategori
            $baikItems = $items->filter(fn($p) => strtolower($p->kondisi?->nama_kondisi ?? '') === 'baik');
            $rusakItems = $items->filter(fn($p) => strtolower($p->kondisi?->nama_kondisi ?? '') === 'rusak');

            $baik_qty = $baikItems->count();
            $baik_harga = $baikItems->sum('harga_beli');
            
            $rusak_qty = $rusakItems->count();
            $rusak_harga = $rusakItems->sum('harga_beli');

            // --- PERBAIKAN UTAMA DISINI ---
            // Hitung Total Baris BERDASARKAN FILTER KONDISI yang aktif
            $total_qty_row = 0;
            $total_harga_row = 0;

            // Jika filter 'all' atau 'baik', masukkan data baik ke total
            if ($filterKondisi === 'all' || $filterKondisi === 'baik') {
                $total_qty_row += $baik_qty;
                $total_harga_row += $baik_harga;
            }

            // Jika filter 'all' atau 'rusak', masukkan data rusak ke total
            if ($filterKondisi === 'all' || $filterKondisi === 'rusak') {
                $total_qty_row += $rusak_qty;
                $total_harga_row += $rusak_harga;
            }

            return [
                'nama_jenis'  => $namaAlat,
                'baik_qty'    => $baik_qty,
                'baik_harga'  => $baik_harga,
                'rusak_qty'   => $rusak_qty,
                'rusak_harga' => $rusak_harga,
                
                // Gunakan hasil perhitungan kondisional di atas
                'total_qty'   => $total_qty_row,
                'total_harga' => $total_harga_row,
            ];
        });

        // Sorting Akhir
        $data = $data->sortBy('nama_jenis', SORT_NATURAL | SORT_FLAG_CASE)->values();

        // Hitung Grand Total (Footer)
        // Karena data 'total_qty' di setiap baris sudah benar (mengikuti filter),
        // maka sum() di sini otomatis juga benar.
        $grandTotal = [
            'baik_qty'    => $data->sum('baik_qty'),
            'baik_harga'  => $data->sum('baik_harga'),
            'rusak_qty'   => $data->sum('rusak_qty'),
            'rusak_harga' => $data->sum('rusak_harga'),
            'total_qty'   => $data->sum('total_qty'),
            'total_harga' => $data->sum('total_harga'),
        ];

        return [
            'resume_data'    => $data,
            'grand_total'    => $grandTotal,
            'filter_kondisi' => $filterKondisi,
            'filter_nama'    => $filterNama,
            'list_nama_alat' => $listNamaAlat,
        ];
    }
}