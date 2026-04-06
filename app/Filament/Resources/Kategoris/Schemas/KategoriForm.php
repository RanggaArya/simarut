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

            ])->columns(2);
    }
}
