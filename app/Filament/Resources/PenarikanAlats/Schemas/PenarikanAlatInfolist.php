<?php

namespace App\Filament\Resources\PenarikanAlats\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\Entry; 

class PenarikanAlatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Entry::make('detail_view')
                    ->view('infolists.view-penarikan-alat')
                    ->columnSpanFull(),
            ]);
    }
}
