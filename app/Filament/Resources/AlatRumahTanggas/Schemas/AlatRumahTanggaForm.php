<?php

namespace App\Filament\Resources\AlatRumahTanggas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

// 1. Cukup import Model Kategori saja, hapus import Set dan Get
use App\Models\Kategori;

class AlatRumahTanggaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Barang')
                    ->schema([
                        TextInput::make('nama_perangkat')
                            ->label('Nama / Jenis Alat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Select::make('kategori_id')
                            ->relationship('kategori', 'nama_kategori')
                            ->label('Kategori')
                            ->searchable()
                            ->preload()
                            ->live() 
                            // 2. Ganti Set dan Get dengan "callable"
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                if ($state) {
                                    $kategori = Kategori::find($state);
                                    if ($kategori && $kategori->masa_pakai_bulan && empty($get('masa_pakai_bulan'))) {
                                        $set('masa_pakai_bulan', $kategori->masa_pakai_bulan);
                                    }
                                }
                            })
                            ->createOptionForm([
                                TextInput::make('nama_kategori')
                                    ->label('Nama Kategori')
                                    ->required(),

                                TextInput::make('kode_kategori')
                                    ->label('Kode Kategori (3 digit)')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(3)
                                    ->regex('/^\d{3}$/')
                                    ->helperText('Masukkan 3 digit angka, contoh: 002'),
                            ]),

                        Select::make('jenis_id')
                            ->relationship('jenis', 'nama_jenis')
                            ->label('Jenis Perangkat')
                            ->searchable()
                            ->preload(),

                        TextInput::make('merek_alat')
                            ->label('Merek Alat')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Lokasi & Inventaris')
                    ->schema([
                        Select::make('lokasi_id')
                            ->relationship('lokasi', 'nama_lokasi')
                            ->label('Lokasi')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('nomor_inventaris')
                            ->label('Nomor Inventaris')
                            ->unique(ignoreRecord: true),

                        Select::make('kondisi_id')
                            ->relationship('kondisi', 'nama_kondisi')
                            ->label('Kondisi Alat'),
                    ])->columns(3),

                Section::make('Data Pengadaan, Harga & Masa Pakai')
                    ->schema([
                        Grid::make(3)->schema([
                            DatePicker::make('tanggal_pengadaan')
                                ->label('Tanggal Pengadaan'),

                            DatePicker::make('tanggal_supervisi')
                                ->label('Tanggal Supervisi'),

                            TextInput::make('tahun_pengadaan')
                                ->label('Tahun Pengadaan')
                                ->numeric()
                                ->length(4),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('sumber_pendanaan')
                                ->label('Sumber Pendanaan')
                                ->placeholder('Contoh: Swadana'),

                            TextInput::make('masa_pakai_bulan')
                                ->label('Masa Pakai (Bulan)')
                                ->numeric()
                                ->helperText('Ditarik dari kategori. *NB: Perhitungan penyusutan HANYA berlaku jika Nilai Perolehan > Rp 2.000.000.'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('harga_beli')
                                ->label('Biaya Awal (Rp)')
                                ->prefix('Rp')
                                ->numeric(),

                            TextInput::make('harga_total')
                                ->label('Nilai Perolehan / Harga Total (Rp)')
                                ->prefix('Rp')
                                ->numeric()
                                ->helperText('Biaya awal + biaya lain. Akan otomatis sama dengan Biaya Awal jika dikosongkan saat simpan.'),
                        ]),
                    ]),

                Section::make('Keterangan')
                    ->schema([
                        Textarea::make('keterangan')
                            ->rows(3),
                        
                        Select::make('status_id')
                            ->relationship('status', 'nama_status')
                            ->label('Status Operasional'),
                    ]),
            ]);
    }
}