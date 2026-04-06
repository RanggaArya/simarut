<?php

namespace App\Filament\Resources\Mutasis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\User as AppUser;
use App\Models\Perangkat;

class MutasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('perangkat_id')
                    ->label('Pilih Perangkat (Nomor Inventaris)')
                    ->relationship('perangkat', 'nomor_inventaris')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->columnSpan('full')
                    ->getOptionLabelUsing(fn($value) => Perangkat::find($value)?->nama_perangkat . ' (' . Perangkat::find($value)?->nomor_inventaris . ')')
                    ->disabled(fn() => request()->query('perangkat_id') !== null)
                    ->afterStateUpdated(function ($state, $set) {
                        if (blank($state)) {
                            $set('nama_perangkat', null);
                            $set('nomor_inventaris', null);
                            $set('kondisi_id', null);
                            $set('lokasi_asal_id', null);
                            return;
                        }

                        $perangkat = Perangkat::with(['kondisi', 'lokasi'])->find($state);
                        if ($perangkat) {
                            $set('nama_perangkat', $perangkat->nama_perangkat);
                            $set('nomor_inventaris', $perangkat->nomor_inventaris);
                            $set('kondisi_id', $perangkat->kondisi_id);
                            $set('lokasi_asal_id', $perangkat->lokasi_id);
                        }
                    }),

                TextInput::make('nama_perangkat')
                    ->label('Nama Perangkat')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('nomor_inventaris')
                    ->label('No. Inventaris')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Select::make('kondisi_id')
                    ->label('Kondisi')
                    ->relationship('kondisi', 'nama_kondisi')
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->dehydrated(),

                Select::make('lokasi_asal_id')
                    ->label('Lokasi Asal (Snapshot)')
                    ->relationship('lokasiAsal', 'nama_lokasi')
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->dehydrated(),

                Select::make('lokasi_mutasi_id')
                    ->label('Lokasi Mutasi (Tujuan)')
                    ->relationship('lokasiMutasi', 'nama_lokasi')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('tanggal_mutasi')
                    ->label('Tanggal Mutasi')
                    ->required()
                    ->default(now())
                    ->native(false),

                DatePicker::make('tanggal_diterima')
                    ->label('Tanggal Diterima')
                    ->nullable()
                    ->native(false),

                Textarea::make('alasan_mutasi')
                    ->label('Alasan Mutasi')
                    ->columnSpan('full'),

                TextInput::make('user_id')
                    ->hidden()
                    ->dehydrated()
                    ->default(fn() => Auth::id()),
            ]);
    }
}
