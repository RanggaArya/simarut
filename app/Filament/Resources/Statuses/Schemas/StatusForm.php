<?php

namespace App\Filament\Resources\Statuses\Schemas;

use Filament\Forms\Components\TextInput;

use Filament\Schemas\Schema;

class StatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_status')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->columnSpan('full'),
            ]);
    }
}
