<?php

namespace App\Filament\Resources\PenarikanAlats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;

class PenarikanAlatsTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('nomor_inventaris')
          ->searchable()->sortable(),
        TextColumn::make('nama_perangkat')
          ->searchable()->limit(30),
        TextColumn::make('lokasi.nama_lokasi')
          ->label('Lokasi Snapshot'),
        TextColumn::make('tanggal_penarikan')
          ->date('d M Y')
          ->sortable(),
        TagsColumn::make('alasan_penarikan')
          ->label('Alasan'),
        TextColumn::make('tindak_lanjut_tipe')
          ->label('Tindak Lanjut')
          ->getStateUsing(function ($record) {
            $tipe = $record->tindak_lanjut_tipe;
            $detail = $record->tindak_lanjut_detail;

            if (in_array($tipe, ['Pindahan', 'Lainnya']) && filled($detail)) {
              return "{$tipe} ({$detail})";
            }

            return $tipe;
          })
          ->wrap(),

      ])
      ->filters([
        //
      ])
      ->recordActions([
        ViewAction::make(),
        EditAction::make(),
        DeleteAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }
}
