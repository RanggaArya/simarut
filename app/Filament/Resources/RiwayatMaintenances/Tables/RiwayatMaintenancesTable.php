<?php

namespace App\Filament\Resources\RiwayatMaintenances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\RiwayatMaintenance;
use Filament\Tables\Filters\SelectFilter;

class RiwayatMaintenancesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('perangkats.nomor_inventaris')
          ->searchable()
          ->label('Nomor Inventaris'),

        TextColumn::make('perangkats.nama_perangkat')
          ->label('Perangkat'),

        TextColumn::make('lokasi.nama_lokasi')
          ->label('Ruangan')
          ->toggleable(),

        TextColumn::make('nama_pemilik')
          ->label('Pemilik/Pengguna')
          ->toggleable(),

        TextColumn::make('maintenanceTypes.nama')
          ->label('Jenis')
          ->badge()
          ->separator(', '),

        TextColumn::make('komponen_summary')
          ->label('Komponen → Aksi (Keterangan)')
          ->wrap()
          ->limit(120)
          ->getStateUsing(function (RiwayatMaintenance $record) {
            $record->loadMissing('komponenDetails.komponen');

            return $record->komponenDetails
              ->map(function ($row) {
                $nama = $row->komponen?->nama ?? '-';
                $aksi = $row->aksi ?: '-';
                $ket  = trim((string) $row->keterangan);
                return $ket ? "{$nama} → {$aksi} ({$ket})" : "{$nama} → {$aksi}";
              })
              ->join('; ');
          }),

        TextColumn::make('status_akhir')
          ->label('Status Akhir')
          ->badge()
          ->formatStateUsing(fn($state) => str_replace('_', ' ', ucfirst($state)))
          ->color(fn($state) => match ($state) {
            'berfungsi' => 'success',
            'berfungsi_sebagian' => 'warning',
            'tidak_berfungsi' => 'danger',
            default => 'gray',
          }),

        TextColumn::make('deskripsi')
          ->label('Deskripsi Pekerjaan')
          ->limit(50),

        TextColumn::make('tanggal_maintenance')
          ->label('Tanggal Maintenance')
          ->date('d M Y')
          ->sortable(),

        TextColumn::make('user.name')
          ->label('Ditambahkan Oleh')
          ->toggleable(),

        TextColumn::make('created_at')
          ->label('Dibuat')
          ->dateTime('d M Y, H:i')
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        SelectFilter::make('perangkat_id')
          ->label('Perangkat')
          ->relationship('perangkats', 'nama_perangkat')
          ->multiple(),

        SelectFilter::make('lokasi_id')
          ->label('Ruangan')
          ->relationship('lokasi', 'nama_lokasi')
          ->multiple(),

        SelectFilter::make('status_akhir')
          ->label('Status')
          ->options([
            'berfungsi' => 'Berfungsi',
            'berfungsi_sebagian' => 'Berfungsi Sebagian',
            'tidak_berfungsi' => 'Tidak Berfungsi',
          ]),
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
