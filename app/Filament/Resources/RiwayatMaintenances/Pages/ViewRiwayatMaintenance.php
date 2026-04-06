<?php

namespace App\Filament\Resources\RiwayatMaintenances\Pages;

use App\Filament\Resources\RiwayatMaintenances\RiwayatMaintenanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRiwayatMaintenance extends ViewRecord
{
    protected static string $resource = RiwayatMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
