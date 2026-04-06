<?php

namespace App\Filament\Resources\AlatRumahTanggas\Pages;

use App\Filament\Resources\AlatRumahTanggas\AlatRumahTanggaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAlatRumahTangga extends ViewRecord
{
    protected static string $resource = AlatRumahTanggaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
