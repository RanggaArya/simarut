<?php

namespace App\Filament\Resources\AlatRumahTanggas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Perangkat;
use Carbon\Carbon;

class AlatRumahTanggaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('nomor_inventaris')
                    ->label('No. Inventaris')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('nama_perangkat')
                    ->label('Nama dan Merek Alat')
                    ->searchable()
                    ->description(fn (Perangkat $record) => $record->merek_alat)
                    ->sortable(),

                TextColumn::make('jenis.nama_jenis')
                    ->label('Jenis')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('lokasi.nama_lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kondisi.nama_kondisi')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baik' => 'success',
                        'Rusak Ringan' => 'warning',
                        'Rusak Berat' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status.nama_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Rusak' => 'danger',
                        'Expired' => 'danger',
                        'Sudah tidak digunakan' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tanggal_pengadaan')
                    ->label('Tgl Pengadaan')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tanggal_supervisi')
                    ->label('Tgl Supervisi')
                    ->sortable()
                    ->placeholder('Belum supervisi')
                    ->formatStateUsing(fn ($state) =>
                        $state
                            ? Carbon::parse($state)->translatedFormat('d M Y')
                            : 'Belum supervisi'
                    )
                    ->color(fn ($state) => $state ? 'success' : 'gray'),


                TextColumn::make('tahun_pengadaan')
                    ->label('Tahun Pengadaan')
                    ->sortable(),

                TextColumn::make('harga_beli')
                    ->label('Harga Beli')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('lokasi_id')
                    ->relationship('lokasi', 'nama_lokasi')
                    ->label('Lokasi'),
                
                SelectFilter::make('kondisi_id')
                    ->relationship('kondisi', 'nama_kondisi')
                    ->label('Kondisi'),

                SelectFilter::make('tahun_pengadaan')
                    ->options(fn() => Perangkat::select('tahun_pengadaan')->distinct()->pluck('tahun_pengadaan', 'tahun_pengadaan')->toArray())
                    ->label('Tahun'),

                SelectFilter::make('jenis_id')
                    ->relationship('jenis', 'nama_jenis')
                    ->label('Jenis'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('Cetak Stiker')
                    ->icon('heroicon-o-printer')
                    ->label('Stiker')
                    ->url(
                        fn(Perangkat $record): string =>
                        route('cetak.satu.stiker', ['perangkat' => $record->id])
                    )
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}