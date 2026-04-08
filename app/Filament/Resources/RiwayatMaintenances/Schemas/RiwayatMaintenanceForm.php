<?php

namespace App\Filament\Resources\RiwayatMaintenances\Schemas;

use Filament\Schemas\Schema;
use App\Models\MaintenanceType;
use App\Models\Komponen;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Models\Perangkat;
use Illuminate\Support\Facades\Auth;
use App\Models\User as AppUser;
use Filament\Forms\Components\Repeater;


class RiwayatMaintenanceForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        Select::make('perangkat_id')
          ->label('Perangkat (Kode Inv)')
          ->relationship('perangkats', 'nomor_inventaris')
          ->required()
          ->preload()
          ->searchable()
          ->getSearchResultsUsing(function (string $query) {
            $q = Perangkat::query()
              ->select(['id', 'nomor_inventaris'])
              ->orderBy('nomor_inventaris');

            if (filled($query)) {
              $q->where('nomor_inventaris', 'like', "%{$query}%");
            }
            return $q->limit(50)->pluck('nomor_inventaris', 'id')->toArray();
          })
          ->getOptionLabelUsing(fn($value) => Perangkat::find($value)?->nama_perangkat)
          ->default(fn() => request()->query('perangkat_id'))
          ->disabled(fn() => request()->query('perangkat_id') !== null)
          ->dehydrated()
          ->live()
          ->reactive()
          ->afterStateUpdated(function ($state, $set) {
            if (blank($state)) {
              $set('lokasi_id', null);
              return;
            }

            $perangkat = Perangkat::select(['id', 'lokasi_id'])->find($state);
            if ($perangkat) {
              $set('lokasi_id', $perangkat->lokasi_id);
            }
          }),

        TextInput::make('user_id')
        ->hidden()
        ->default(fn () => Auth::id())
        ->dehydrated(true),

        DatePicker::make('tanggal_maintenance')
          ->label('Tanggal Maintenance')
          ->required()
          ->native(false),

        Select::make('lokasi_id')
          ->label('Lokasi Ruangan')
          ->relationship('lokasi', 'nama_lokasi')
          ->searchable()
          ->preload()
          ->disabled()
          ->dehydrated()
          ->default(fn() => request()->query('lokasi_id')),

        TextInput::make('nama_pemilik')
          ->label('Nama Pemilik/Pengguna Alat')
          ->maxLength(150)
          ->nullable(),

        Select::make('maintenanceTypes')
          ->label('Jenis Maintenance')
          ->relationship('maintenanceTypes', 'nama')
          ->multiple()
          ->preload()
          ->required()
          ->createOptionForm([
            TextInput::make('nama')
              ->label('Nama Jenis')
              ->required()
              ->unique(MaintenanceType::class, 'nama'),
          ]),
          // ->createOptionAction(function ($action) {
          //   $user = Auth::user();
          //   $can = $user instanceof AppUser && $user->canDo('maintenance.create');

          //   $action->visible($can);
          //   $action->modalHeading('Tambah Komponen');
          // }),
        Select::make('status_akhir')
          ->label('Status Akhir')
          ->options([
            'berfungsi' => 'Berfungsi',
            'berfungsi_sebagian' => 'Berfungsi Sebagian',
            'tidak_berfungsi' => 'Tidak Berfungsi',
          ])
          ->nullable(),
        Repeater::make('komponenDetails')
          ->label('Komponen Dicek/Diganti')
          ->relationship('komponenDetails')
          ->defaultItems(1)
          ->minItems(0)
          ->reorderable(false)
          ->columnSpan('full')
          ->schema([
            Select::make('komponen_id')
              ->label('Komponen')
              ->relationship('komponen', 'nama')
              ->searchable()
              ->preload()
              ->required()
              ->createOptionForm([
                TextInput::make('nama')
                  ->label('Nama Komponen')
                  ->required()
                  ->unique(Komponen::class, 'nama'),
              ]),
              // ->createOptionAction(function ($action) {
              //   $user = Auth::user();
              //   $can = $user instanceof AppUser && $user->canDo('maintenance.create');

              //   $action->visible($can);
              //   $action->modalHeading('Tambah Komponen');
              // }),

            Select::make('aksi')
              ->label('Aksi')
              ->options([
                'dicek'   => 'Dicek',
                'diganti' => 'Diganti',
              ])
              ->required(),

            Textarea::make('keterangan')
              ->label('Keterangan / Hasil Cek')
              ->rows(2)
              ->columnSpan('full')
              ->nullable(),
          ])
          ->columns([
            'default' => 1,
            'md' => 3,
          ]),

        Textarea::make('deskripsi')
          ->label('Deskripsi Pekerjaan')
          ->required()
          ->columnSpan('full'),

        Textarea::make('catatan')
          ->label('Catatan Tambahan')
          ->nullable()
          ->columnSpan('full'),

        FileUpload::make('foto')
          ->label('Foto (opsional)')
          ->disk('public')
          ->visibility('public')
          ->multiple()
          ->image()
          ->imageEditor()
          ->downloadable()
          ->reorderable()
          ->directory('maintenance-photos')
          ->nullable()
          ->columnSpan('full'),
      ]);
  }
}
