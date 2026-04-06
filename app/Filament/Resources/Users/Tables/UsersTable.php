<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;

class UsersTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role'),
            ])
            // v4: pakai recordActions (bukan Tables\Actions\Action)
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->visible(fn ($record) => UserResource::canEdit($record))
                    ->url(fn ($record) => UserResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->visible(fn () => UserResource::canDeleteAny())
                    ->action(fn (Collection $records) => $records->each->delete()),
            ]);
    }
}
