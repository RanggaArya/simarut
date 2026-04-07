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
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tanggal_supervisi')
                    ->label('Tgl Supervisi')
                    ->getStateUsing(function (Perangkat $record) {
                        // 1. Cek apakah harga di bawah/sama dengan 2 Juta (Ekstrakomptabel)
                        if (!$record->is_kena_penyusutan) {
                            return 'Tidak Wajib';
                        }
                        
                        // 2. Jika Wajib (> 2 Juta), cek apakah ada tanggalnya
                        return $record->tanggal_supervisi_aktif 
                            ? Carbon::parse($record->tanggal_supervisi_aktif)->translatedFormat('d M Y') 
                            : 'Belum Supervisi';
                    })
                    ->badge() // Membuatnya jadi kotak warna-warni yang profesional
                    ->color(function (Perangkat $record) {
                        // Atur warna otomatis berdasarkan kondisinya
                        if (!$record->is_kena_penyusutan) {
                            return 'gray'; // Abu-abu untuk yang tidak wajib
                        }
                        return $record->tanggal_supervisi_aktif ? 'success' : 'danger'; // Hijau jika ada, Merah jika belum
                    })
                    ->sortable(),

                TextColumn::make('tahun_pengadaan')
                    ->label('Tahun')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('harga_beli')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('harga_total')
                    ->label('Nilai Perolehan')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 👇 MODIFIKASI: MASA PAKAI DIBUAT PINTAR 👇
                TextColumn::make('masa_pakai_bulan')
                    ->label('Masa Pakai')
                    ->getStateUsing(fn (Perangkat $record) => $record->masa_pakai_aktif)
                    ->formatStateUsing(fn (Perangkat $record, $state) => 
                        !$record->is_kena_penyusutan ? 'Ekstrakomptabel' : $state . ' Bulan'
                    )
                    ->badge()
                    ->color(fn (Perangkat $record) => !$record->is_kena_penyusutan ? 'warning' : 'info')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 👇 TAMBAHAN BARU: NILAI RESIDU TERKINI 👇
                TextColumn::make('harga_residu')
                    ->label('Nilai Saat Ini (Residu)')
                    ->getStateUsing(fn (Perangkat $record) => $record->harga_residu)
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn (Perangkat $record) => 
                        !$record->is_kena_penyusutan ? 'gray' : ($record->harga_residu <= 0 ? 'danger' : 'success')
                    )
                    // Jika ekstrakomptabel, beri keterangan kecil di bawah harganya
                    ->description(fn (Perangkat $record) => 
                        !$record->is_kena_penyusutan ? 'Tidak disusutkan' : 
                        ($record->harga_residu <= 0 ? 'Penyusutan Selesai' : 'Sisa: ' . $record->sisa_masa_pakai . ' Bulan')
                    ),
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