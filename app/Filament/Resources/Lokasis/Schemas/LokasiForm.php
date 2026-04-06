<?php

namespace App\Filament\Resources\Lokasis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LokasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_lokasi')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->columnSpan('full'),
                Textarea::make('deskripsi_lokasi')
                    ->rows(3)
                    ->columnSpan('full'),
            ]);
    }
}
