<?php

namespace App\Filament\Resources\Statuses\Pages;

use App\Filament\Resources\Statuses\StatusResource;
use App\Filament\Resources\Statuses\Schemas\StatusForm;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListStatuses extends ListRecords
{
    protected static string $resource = StatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Tambah Status')
                ->icon('heroicon-o-plus')
                ->modalHeading('Tambah Status')
                ->modalSubmitActionLabel('Simpan')
                ->modalWidth('md')
                ->createAnother(false),
        ];
    }
}
