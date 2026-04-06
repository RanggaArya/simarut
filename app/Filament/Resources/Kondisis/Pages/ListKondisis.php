<?php

namespace App\Filament\Resources\Kondisis\Pages;

use App\Filament\Resources\Kondisis\KondisiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKondisis extends ListRecords
{
    protected static string $resource = KondisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Kondisi')
                ->icon('heroicon-o-plus')
                ->modalHeading('Tambah Kondisi')
                ->modalSubmitActionLabel('Simpan')
                ->modalWidth('xl')
                ->createAnother(false),
        ];
    }
}
