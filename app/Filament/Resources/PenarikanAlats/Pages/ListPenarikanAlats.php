<?php

namespace App\Filament\Resources\PenarikanAlats\Pages;

use App\Filament\Resources\PenarikanAlats\PenarikanAlatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenarikanAlats extends ListRecords
{
    protected static string $resource = PenarikanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
