<?php

namespace App\Filament\Resources\AlatRumahTanggas\Pages;

use App\Filament\Resources\AlatRumahTanggas\AlatRumahTanggaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAlatRumahTangga extends EditRecord
{
    protected static string $resource = AlatRumahTanggaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
