<?php

namespace App\Filament\Resources\PenarikanAlats\Pages;

use App\Filament\Resources\PenarikanAlats\PenarikanAlatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPenarikanAlat extends ViewRecord
{
    protected static string $resource = PenarikanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
