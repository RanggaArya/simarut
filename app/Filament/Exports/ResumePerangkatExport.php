<?php

namespace App\Filament\Exports;

use App\Models\Perangkat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ResumePerangkatExport implements FromCollection, WithCustomStartCell, WithMapping, WithStyles, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    protected $filterKondisi;
    protected $filterNama;
    protected $rowNumber = 0;
    
    // Variabel untuk menyimpan Total Keseluruhan
    protected $grandTotalData = [
        'baik_qty' => 0, 'baik_harga' => 0,
        'rusak_qty' => 0, 'rusak_harga' => 0,
        'total_qty' => 0, 'total_harga' => 0,
    ];

    public function __construct($data)
    {
        $this->filterKondisi = $data['filter_kondisi'] ?? 'all';
        $this->filterNama    = $data['filter_nama'] ?? [];
    }

    public function startCell(): string
    {
        return 'A4'; // Data mulai baris 4
    }

    public function collection()
    {
        $query = Perangkat::with(['kondisi'])->orderBy('nama_perangkat', 'asc');

        if (!empty($this->filterNama)) {
            $query->whereIn('nama_perangkat', $this->filterNama);
        }

        $allPerangkats = $query->get();
        $groupedData = $allPerangkats->groupBy('nama_perangkat');

        // 1. OLAH DATA BARIS
        $data = $groupedData->map(function ($items, $namaAlat) {
            $baikItems = $items->filter(fn($p) => strtolower($p->kondisi?->nama_kondisi ?? '') === 'baik');
            $rusakItems = $items->filter(fn($p) => strtolower($p->kondisi?->nama_kondisi ?? '') === 'rusak');

            $baik_qty = $baikItems->count();
            $baik_harga = $baikItems->sum('harga_beli');
            $rusak_qty = $rusakItems->count();
            $rusak_harga = $rusakItems->sum('harga_beli');

            $total_qty_row = 0;
            $total_harga_row = 0;

            if ($this->filterKondisi === 'all' || $this->filterKondisi === 'baik') {
                $total_qty_row += $baik_qty;
                $total_harga_row += $baik_harga;
            }
            if ($this->filterKondisi === 'all' || $this->filterKondisi === 'rusak') {
                $total_qty_row += $rusak_qty;
                $total_harga_row += $rusak_harga;
            }

            return [
                'nama_jenis'  => $namaAlat,
                'baik_qty'    => $baik_qty,
                'baik_harga'  => $baik_harga,
                'rusak_qty'   => $rusak_qty,
                'rusak_harga' => $rusak_harga,
                'total_qty'   => $total_qty_row,
                'total_harga' => $total_harga_row,
            ];
        });

        // Sort Data
        $sortedData = $data->sortBy('nama_jenis', SORT_NATURAL | SORT_FLAG_CASE)->values();

        // 2. HITUNG GRAND TOTAL
        $this->grandTotalData['baik_qty']    = $sortedData->sum('baik_qty');
        $this->grandTotalData['baik_harga']  = $sortedData->sum('baik_harga');
        $this->grandTotalData['rusak_qty']   = $sortedData->sum('rusak_qty');
        $this->grandTotalData['rusak_harga'] = $sortedData->sum('rusak_harga');
        $this->grandTotalData['total_qty']   = $sortedData->sum('total_qty');
        $this->grandTotalData['total_harga'] = $sortedData->sum('total_harga');

        // 3. TAMBAHKAN BARIS GRAND TOTAL KE KOLEKSI DATA
        $sortedData->push([
            'nama_jenis' => 'GRAND TOTAL', // Flag
            'baik_qty' => $this->grandTotalData['baik_qty'],
            'baik_harga' => $this->grandTotalData['baik_harga'],
            'rusak_qty' => $this->grandTotalData['rusak_qty'],
            'rusak_harga' => $this->grandTotalData['rusak_harga'],
            'total_qty' => $this->grandTotalData['total_qty'],
            'total_harga' => $this->grandTotalData['total_harga'],
        ]);

        return $sortedData;
    }

    public function map($row): array
    {
        // Masukkan teks GRAND TOTAL ke Kolom A (karena A & B di-merge)
        if ($row['nama_jenis'] === 'GRAND TOTAL') {
            $colA = 'GRAND TOTAL'; 
            $colB = ''; 
        } else {
            $this->rowNumber++;
            $colA = $this->rowNumber;
            $colB = $row['nama_jenis'];
        }

        $mapped = [
            $colA, 
            $colB,
        ];

        if ($this->filterKondisi === 'all' || $this->filterKondisi === 'baik') {
            $mapped[] = $row['baik_qty'];
            $mapped[] = $row['baik_harga'];
        }

        if ($this->filterKondisi === 'all' || $this->filterKondisi === 'rusak') {
            $mapped[] = $row['rusak_qty'];
            $mapped[] = $row['rusak_harga'];
        }

        $mapped[] = $row['total_qty'];
        $mapped[] = $row['total_harga'];

        return $mapped;
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function columnFormats(): array
    {
        // REVISI FORMAT RUPIAH:
        // "Rp " #,##0    -> Menampilkan "Rp 200.000" (Rapat kanan, Rp nempel angka)
        // "Rp " 0        -> Menampilkan "Rp 0" jika nilainya nol
        $formatRupiah = '"Rp "#,##0_-;"Rp "-#,##0_-;"Rp "0_-'; 

        $formats = [];
        $colIndex = 2; // Mulai Col C

        if ($this->filterKondisi === 'all' || $this->filterKondisi === 'baik') {
            $colIndex++; 
            $formats[$this->numToAlpha($colIndex)] = $formatRupiah; 
            $colIndex++; 
        }

        if ($this->filterKondisi === 'all' || $this->filterKondisi === 'rusak') {
            $colIndex++; 
            $formats[$this->numToAlpha($colIndex)] = $formatRupiah; 
            $colIndex++;
        }

        $colIndex++; 
        $formats[$this->numToAlpha($colIndex)] = $formatRupiah; 

        return $formats;
    }

    private function numToAlpha($n)
    {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n % 26 + 0x41) . $r;
        return $r;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // === DEFINISI KOLOM ===
                $endColCondition = 'C'; 
                $startGrandTotal = 'D';
                $endGrandTotal = 'E';

                if ($this->filterKondisi === 'all') {
                    $endColCondition = 'F'; 
                    $startGrandTotal = 'G';
                    $endGrandTotal = 'H';
                } elseif ($this->filterKondisi === 'baik' || $this->filterKondisi === 'rusak') {
                    $endColCondition = 'D'; 
                    $startGrandTotal = 'E';
                    $endGrandTotal = 'F';
                }

                // === HEADER MANUAL ===
                $sheet->setCellValue('A1', 'NO'); $sheet->mergeCells('A1:A3');
                $sheet->setCellValue('B1', 'NAMA ALAT'); $sheet->mergeCells('B1:B3');
                $sheet->setCellValue('C1', 'KONDISI ALAT'); $sheet->mergeCells("C1:{$endColCondition}1");

                if ($this->filterKondisi === 'all') {
                    $sheet->setCellValue('C2', 'BAIK'); $sheet->mergeCells('C2:D2');
                    $sheet->setCellValue('C3', 'JUMLAH'); $sheet->setCellValue('D3', 'HARGA BELI');
                    $sheet->setCellValue('E2', 'RUSAK'); $sheet->mergeCells('E2:F2');
                    $sheet->setCellValue('E3', 'JUMLAH'); $sheet->setCellValue('F3', 'HARGA BELI');
                } elseif ($this->filterKondisi === 'baik') {
                    $sheet->setCellValue('C2', 'BAIK'); $sheet->mergeCells('C2:D2');
                    $sheet->setCellValue('C3', 'JUMLAH'); $sheet->setCellValue('D3', 'HARGA BELI');
                } elseif ($this->filterKondisi === 'rusak') {
                    $sheet->setCellValue('C2', 'RUSAK'); $sheet->mergeCells('C2:D2');
                    $sheet->setCellValue('C3', 'JUMLAH'); $sheet->setCellValue('D3', 'HARGA BELI');
                }

                $sheet->setCellValue("{$startGrandTotal}1", 'GRAND TOTAL');
                $sheet->mergeCells("{$startGrandTotal}1:{$endGrandTotal}2");
                $sheet->setCellValue("{$startGrandTotal}3", 'JUMLAH');
                $sheet->setCellValue("{$endGrandTotal}3", 'HARGA BELI');

                // === STYLING HEADER ===
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']], 
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ];
                $sheet->getStyle("A1:{$endGrandTotal}3")->applyFromArray($headerStyle);

                // Warna Header Kategori
                if ($this->filterKondisi === 'all' || $this->filterKondisi === 'baik') {
                    $sheet->getStyle('C2')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']]]);
                }
                if ($this->filterKondisi === 'all') {
                    $sheet->getStyle('E2')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EF4444']]]);
                } elseif ($this->filterKondisi === 'rusak') {
                    $sheet->getStyle('C2')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EF4444']]]);
                }

                // === FORMATTING DATA ===
                $lastRow = 4 + $this->rowNumber; 

                // Border untuk semua data
                $sheet->getStyle("A4:{$endGrandTotal}{$lastRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                // Alignment NO (Center)
                $lastDataRow = $lastRow - 1;
                $sheet->getStyle("A4:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // === STYLING BARIS GRAND TOTAL ===
                $sheet->mergeCells("A{$lastRow}:B{$lastRow}");
                
                $sheet->getStyle("A{$lastRow}:{$endGrandTotal}{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']], 
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle("A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // === AUTO FILTER (Kecuali Baris Total) ===
                if ($lastDataRow >= 4) { 
                    $sheet->setAutoFilter("A3:{$endGrandTotal}{$lastDataRow}");
                }

                $sheet->freezePane('A4');
            },
        ];
    }
}