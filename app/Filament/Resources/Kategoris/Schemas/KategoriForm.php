<?php

namespace App\Filament\Resources\Kategoris\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kategori')
                ->label('Nama Kategori')
                ->required()
                ->unique(ignoreRecord: true)
                ->columnSpan('full'),
            TextInput::make('kode_kategori')
                ->label('Kode Kategori (3 digit)')
                ->required()
                ->mask('999')
                ->unique(ignoreRecord: true)
                ->columnSpan('full'),
            TextInput::make('masa_pakai_bulan')
                ->label('Masa Pakai Default (Bulan)')
                ->numeric()
                ->nullable()
                ->helperText('Akan digunakan sebagai default masa pakai saat import data perangkat.')
                ->columnSpan('full'),
            ])->columns(2);
    }
}
