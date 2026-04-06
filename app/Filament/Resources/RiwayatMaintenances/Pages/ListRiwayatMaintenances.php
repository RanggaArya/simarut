<?php

namespace App\Filament\Resources\RiwayatMaintenances\Pages;

use App\Filament\Resources\RiwayatMaintenances\RiwayatMaintenanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatMaintenances extends ListRecords
{
    protected static string $resource = RiwayatMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
