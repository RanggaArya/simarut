<?php

namespace App\Filament\Resources\Mutasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MutasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_inventaris')
                    ->label('No. Inventaris')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_perangkat')
                    ->label('Nama Perangkat')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('lokasiAsal.nama_lokasi')
                    ->label('Lokasi Asal')
                    ->sortable(),
                TextColumn::make('lokasiMutasi.nama_lokasi')
                    ->label('Lokasi Tujuan')
                    ->sortable(),
                TextColumn::make('kondisi.nama_kondisi')
                    ->label('Kondisi')
                    ->badge(),
                TextColumn::make('tanggal_mutasi')
                    ->label('Tgl. Mutasi')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('tanggal_diterima')
                    ->label('Tgl. Diterima')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('alasan_mutasi')
                    ->label('Alasan Mutasi')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('Dicatat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
