<?php

namespace App\Filament\Resources\PenarikanAlats\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use App\Models\User as AppUser;
use App\Models\PenarikanAlat;
use App\Models\Perangkat;

class PenarikanAlatForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        Select::make('perangkat_id')
          ->label('Pilih Perangkat (Kode Inv.)')
          ->relationship('perangkat', 'nomor_inventaris')
          ->searchable()
          ->preload()
          ->live()
          ->required()
          ->columnSpan('full')
          ->getOptionLabelUsing(fn($value) => Perangkat::find($value)?->nama_perangkat . ' (' . Perangkat::find($value)?->nomor_inventaris . ')')
          ->afterStateUpdated(function ($state, $set) {
            if (blank($state)) return;
            $perangkat = Perangkat::with('lokasi')->find($state);
            if ($perangkat) {
              $set('nama_perangkat', $perangkat->nama_perangkat);
              $set('nomor_inventaris', $perangkat->nomor_inventaris);
              $set('merek', $perangkat->merek_alat);
              // $set('spesifikasi', $perangkat->keterangan);
              $set('lokasi_id', $perangkat->lokasi_id);
              $set('tahun_pengadaan', $perangkat->tahun_pengadaan);
            }
          }),

        TextInput::make('nama_perangkat')
          ->label('Nama Perangkat')
          ->disabled()->dehydrated(),
        TextInput::make('nomor_inventaris')
          ->label('No. Inventaris')
          ->disabled()->dehydrated(),
        TextInput::make('merek')
          ->label('Merek')
          ->disabled()->dehydrated(),
        Select::make('lokasi_id')
          ->label('Lokasi Terakhir')
          ->relationship('lokasi', 'nama_lokasi')
          ->disabled()->dehydrated(),
        TextInput::make('tahun_pengadaan')
          ->label('Tahun Pengadaan')
          ->disabled()->dehydrated(),
        // Textarea::make('spesifikasi')
        //   ->label('Spesifikasi')
        //   ->disabled()->dehydrated()
        //   ->columnSpan('full'),

        DatePicker::make('tanggal_penarikan')
          ->label('Tanggal Penarikan')
          ->required()
          ->default(now())
          ->native(false),

        CheckboxList::make('alasan_penarikan')
          ->label('Alasan Penarikan')
          ->options([
            'Rusak' => 'Rusak',
            'Tidak Layak Pakai' => 'Tidak Layak Pakai',
            'Melebihi Masa Pakai' => 'Melebihi Masa Pakai',
          ])
          ->columns(3),

        Textarea::make('alasan_lainnya')
          ->label('Alasan Lainnya (jika tidak ada isi dengan "-")')
          ->nullable()
          ->rows(2)
          ->columnSpan('full'),

        Radio::make('tindak_lanjut_tipe')
          ->label('Tindak Lanjut')
          ->options([
            'Perbaikan' => 'Perbaikan',
            'Ganti Baru' => 'Ganti Baru',
            'Pindahan' => 'Pindahan',
            'Lainnya' => 'Lainnya',
          ])
          ->required()
          ->live(),

        Textarea::make('tindak_lanjut_detail')
          ->label('Detail Tindak Lanjut')
          ->placeholder(fn($get): string => match ($get('tindak_lanjut_tipe')) {
            'Pindahan' => 'Diisi Pindahan dari Unit...',
            'Lainnya' => 'Diisi keterangan lainnya...',
            default => 'Detail (opsional)',
          })
          ->visible(fn($get) => in_array($get('tindak_lanjut_tipe'), ['Pindahan', 'Lainnya']))
          ->rows(2)
          ->columnSpan('full'),

        TextInput::make('user_id')
          ->hidden()
          ->dehydrated()
          ->default(fn() => Auth::id()),

      ]);
  }
}
