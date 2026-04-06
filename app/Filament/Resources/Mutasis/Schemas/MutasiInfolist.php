<?php

namespace App\Filament\Resources\Mutasis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Entry; 
use Filament\Schemas\Schema;

class MutasiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Entry::make('detail_view')
                    ->view('infolists.mutasi')
                    ->columnSpanFull(),
            ]);
    }
}
