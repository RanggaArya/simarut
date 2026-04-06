<?php

namespace App\Filament\Resources\AlatRumahTanggas\Schemas;

// --- IMPORT DARI INFOLISTS ---
// use Filament\Infolists\Components\Grid;
// use Filament\Infolists\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\Entry;

class AlatRumahTanggaInfolist
{
    // public static function configure(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             Section::make('Detail Barang')
    //                 ->schema([
    //                     Grid::make(3)
    //                         ->schema([
    //                             TextEntry::make('nomor_inventaris')->label('No. Inventaris')->weight('bold'),
    //                             TextEntry::make('nama_alat')->label('Nama Barang'),
    //                             TextEntry::make('merek_alat')->label('Merek'),
    //                         ]),
                        
    //                     Grid::make(3)
    //                         ->schema([
    //                             TextEntry::make('lokasi.nama_lokasi')->label('Lokasi')->badge(),
    //                             TextEntry::make('kondisi.nama_kondisi')->label('Kondisi')->badge(),
    //                             TextEntry::make('status.nama_status')->label('Status Operasional'),
    //                         ]),
    //                 ]),

    //             Section::make('Data Pengadaan')
    //                 ->schema([
    //                     TextEntry::make('tanggal_pengadaan')->date()->label('Tanggal Terima'),
    //                     TextEntry::make('tanggal_supervisi')->date()->label('Tanggal Supervisi'),
    //                     TextEntry::make('tahun_pengadaan')->label('Tahun'),
    //                     TextEntry::make('sumber_pendanaan')->label('Sumber Dana'),
    //                     TextEntry::make('harga_beli')->money('IDR')->label('Harga Perolehan'),
    //                 ])->columns(3),
                
    //             Section::make('Keterangan')
    //                 ->schema([
    //                     TextEntry::make('keterangan'),
    //                 ])->collapsible(),
    //         ]);
    // }
        public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Entry::make('detail_view')
                    ->view('infolists.alat-rumahtangga-detail')
                    ->columnSpanFull(),
            ]);
    }
}