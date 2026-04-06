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
            'No',
            'Lokasi',
            'Nomor Inventaris',
            'Jenis',
            'Nama Alat',
            'Kategori',
            'Kode Kategori',
            'Merek Alat',
            'Kondisi Alat',
            'Status',
            'Tanggal Pengadaan',
            'Tahun Pengadaan',  
            'Sumber Pendanaan',
            'Harga Beli',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        $this->no++;

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
            $row->tahun_pengadaan,
            $row->sumber_pendanaan,
            (int) $row->harga_beli,
            $row->keterangan,
        ];
    }

    public function columnFormats(): array
    {
        return [
        'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function registerEvents(): array
    {
        return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $sheet->insertNewRowBefore(1, 1);

            $title = 'DATA REKAPAN SARANA - PRASARANA NON ALKES';
            $titleCell = 'A1';
            $titleRange = 'A1:' . $highestColumn . '1';

            $sheet->mergeCells($titleRange);

            $sheet->setCellValue($titleCell, $title);

            $titleStyle = $sheet->getStyle($titleRange);
            $titleStyle->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFFFFF00');
            $titleStyle->getFont()
            ->setBold(true)
            ->setSize(13);
            $titleStyle->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension('1')->setRowHeight(22);

            $headerRange = 'A2:' . $highestColumn . '2';
            $headerStyle = $sheet->getStyle($headerRange);

            $headerStyle->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF1F4E78');
            $headerStyle->getFont()
            ->setBold(true)
            ->setSize(11)
            ->getColor()
            ->setARGB('FFFFFFFF');
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

            $sheet->getStyle('A3:A' . $newHighestRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('Q3:R' . $newHighestRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle('B3:B' . $newHighestRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N3:N' . $newHighestRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

            $sheet->getPageMargins()
            ->setLeft(0.5)
            ->setRight(0.5)
            ->setTop(0.75)
            ->setBottom(0.75);

            $sheet->freezePane('A3');

            $sheet->setAutoFilter('A2:' . $highestColumn . '2');

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

