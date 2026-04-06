<?php

namespace App\Filament\Resources\Mutasis\Pages;

use App\Filament\Resources\Mutasis\MutasiResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Perangkat;
use Illuminate\Support\Facades\Auth;

class CreateMutasi extends CreateRecord
{
    protected static string $resource = MutasiResource::class;

    public function mount(): void
    {
      parent::mount();

      if ($perangkatId = request()->query('perangkat_id')) {
        $perangkat = Perangkat::find($perangkatId);

        if ($perangkat){
          $this->form->fill([
            'perangkat_id' => $perangkat->id,
            'nama_perangkat' => $perangkat->nama_perangkat,
            'nomor_inventaris' => $perangkat->nomor_inventaris,
            'kondisi_id' => $perangkat->kondisi_id,
            'lokasi_asal_id' => $perangkat->lokasi_id,
          ]);
        }
      }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
      $data['user_id'] = $data['user_id'] ?? Auth::id();
      return $data;
    }

    protected function afterCreate(): void {
      $record = $this->record;

      if($record->perangkat && $record->lokasi_mutasi_id) {
        $perangkat = $record->perangkat;
        $perangkat->lokasi_id = $record->lokasi_mutasi_id;
        $perangkat->save();
      }
    }

    protected function getRedirectUrl(): string
    {
      return $this->getResource()::getUrl('index');
    }
}
