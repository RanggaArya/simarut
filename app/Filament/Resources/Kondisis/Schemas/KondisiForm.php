<?php

namespace App\Filament\Resources\Kondisis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KondisiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kondisi')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->columnSpan('full'),
            ]);
    }
}
