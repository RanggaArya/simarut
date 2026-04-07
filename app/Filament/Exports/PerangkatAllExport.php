<?php

namespace App\Filament\Exports;

use App\Models\Perangkat;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class PerangkatAllExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents
{
    private int $no = 0;
    private ?string $password;

    public function __construct(?string $password = null)
    {
        $this->password = $password;
    }

    public function query(): Builder
    {
        return Perangkat::query()
            ->with(['lokasi', 'jenis', 'status', 'kondisi', 'kategori'])
            ->orderBy('id', 'asc');
    }

    public function headings(): array
    {
        return [
            'No',                 // A
            'Lokasi',             // B
            'Nomor Inventaris',   // C
            'Jenis',              // D
            'Nama Alat',          // E
            'Kategori',           // F
            'Kode Kategori',      // G
            'Merek Alat',         // H
            'Kondisi Alat',       // I
            'Status',             // J
            'Tanggal Pengadaan',  // K
            'Tanggal Supervisi',  // L (Baru)
            'Tahun Pengadaan',    // M
            'Sumber Pendanaan',   // N
            'Harga Beli',         // O
            'Harga Total',        // P (Baru)
            'Masa Pakai Bulan',   // Q (Baru)
            'Status Masa Pakai',  // R (Baru)
            'Depresiasi / Bulan', // S (Baru)
            'Nilai Aset (Residu)',// T (Baru)
            'Keterangan',         // U
        ];
    }

    public function map($row): array
    {
        $this->no++;

        // 1. Logika Tanggal Supervisi
        $tglSupervisi = 'Belum Supervisi';
        if (!$row->is_kena_penyusutan) {
            $tglSupervisi = 'Tidak Wajib';
        } elseif ($row->tanggal_supervisi_aktif) {
            $tglSupervisi = \Carbon\Carbon::parse($row->tanggal_supervisi_aktif)->format('d-m-Y');
        }

        // 2. Logika Harga Basis (Total atau Beli)
        $basis_harga = $row->harga_total ?? $row->harga_beli ?? 0;
        
        // 3. Logika Depresiasi per Bulan
        $mp = $row->masa_pakai_aktif;
        $depresiasi_per_bulan = ($row->is_kena_penyusutan && $mp > 0) ? ($basis_harga / $mp) : 0;

        // 4. Logika Status Masa Pakai
        $statusMasaPakai = 'Aset Utuh';
        if ($row->is_kena_penyusutan) {
            $statusMasaPakai = $row->sisa_masa_pakai <= 0 ? 'Penyusutan Selesai' : 'Sisa ' . $row->sisa_masa_pakai . ' Bulan';
        }

        return [
            $this->no,
            $row->lokasi?->nama_lokasi,
            $row->nomor_inventaris,
            $row->jenis?->nama_jenis,
            $row->nama_perangkat,
            $row->kategori?->nama_kategori,
            $row->kategori?->kode_kategori,
            $row->merek_alat,
            $row->kondisi?->nama_kondisi,
            $row->status?->nama_status,
            optional($row->tanggal_pengadaan)->format('d-m-Y'),
            $tglSupervisi, // Kolom L
            $row->tahun_pengadaan,
            $row->sumber_pendanaan,
            (int) $row->harga_beli,
            (int) $basis_harga, // Kolom P: Harga Total
            !$row->is_kena_penyusutan ? 'Ekstrakomptabel' : $mp . ' Bulan', // Kolom Q
            $statusMasaPakai, // Kolom R
            (int) round($depresiasi_per_bulan), // Kolom S
            (int) $row->harga_residu, // Kolom T
            $row->keterangan,
        ];
    }

    public function columnFormats(): array
    {
        // Memberikan format separator ribuan (1,000,000) pada Excel untuk kolom harga
        return [
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Harga Beli
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Harga Total
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Depresiasi
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Residu
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Insert baris untuk Judul
                $sheet->insertNewRowBefore(1, 1);

                $title = 'DATA REKAPAN SARANA - PRASARANA NON ALKES';
                $titleCell = 'A1';
                $titleRange = 'A1:' . $highestColumn . '1';

                $sheet->mergeCells($titleRange);
                $sheet->setCellValue($titleCell, $title);

                // Styling Judul
                $titleStyle = $sheet->getStyle($titleRange);
                $titleStyle->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFFFF00'); // Latar Kuning
                $titleStyle->getFont()
                    ->setBold(true)
                    ->setSize(13);
                $titleStyle->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension('1')->setRowHeight(22);

                // Styling Header Tabel (Baris ke-2)
                $headerRange = 'A2:' . $highestColumn . '2';
                $headerStyle = $sheet->getStyle($headerRange);

                $headerStyle->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FF1F4E78'); // Latar Biru Tua
                $headerStyle->getFont()
                    ->setBold(true)
                    ->setSize(11)
                    ->getColor()
                    ->setARGB('FFFFFFFF'); // Teks Putih
                $headerStyle->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setARGB('FF000000');
                $headerStyle->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $sheet->getRowDimension('2')->setRowHeight(25);

                $newHighestRow = $sheet->getHighestRow();
                $lightGrayFill = 'FFF2F2F2';

                // Zebra striping dan Border untuk Data
                for ($row = 3; $row <= $newHighestRow; $row++) {
                    if ($row % 2 == 1) {
                        $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB($lightGrayFill);
                    }

                    $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)
                        ->getBorders()->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()
                        ->setARGB('FFD3D3D3');
                }

                // --- ALIGNMENT TEXT DI EXCEL ---
                // Tengah untuk No, Lokasi, dll yang pendek
                $sheet->getStyle('A3:B' . $newHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Tengah untuk Tanggal, Tahun, Masa Pakai & Status
                $sheet->getStyle('K3:M' . $newHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('Q3:R' . $newHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Kanan untuk Kolom Keuangan/Harga (O, P, S, T)
                $sheet->getStyle('O3:P' . $newHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('S3:T' . $newHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Setup Print Layout
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $sheet->getPageMargins()
                    ->setLeft(0.5)->setRight(0.5)->setTop(0.75)->setBottom(0.75);

                $sheet->freezePane('A3');
                $sheet->setAutoFilter('A2:' . $highestColumn . '2');

                // Password Protection
                if ($this->password) {
                    $sheet->getProtection()->setPassword($this->password);
                    $sheet->getProtection()->setSheet(true);
                    $sheet->getProtection()->setObjects(true);
                    $sheet->getProtection()->setScenarios(true);
                }
            },
        ];
    }
}