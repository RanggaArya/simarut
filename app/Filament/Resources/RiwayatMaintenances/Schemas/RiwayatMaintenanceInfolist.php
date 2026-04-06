<?php

namespace App\Filament\Resources\RiwayatMaintenances\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\Entry; 

class RiwayatMaintenanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Entry::make('detail_view')
                    ->view('infolists.maintenance')
                    ->columnSpanFull(),
            ]);
    }
}
