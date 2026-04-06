<?php

namespace App\Filament\Resources\PenarikanAlats\Pages;

use App\Filament\Resources\PenarikanAlats\PenarikanAlatResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;


class CreatePenarikanAlat extends CreateRecord
{
  protected static string $resource = PenarikanAlatResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = $data['user_id'] ?? Auth::id();
    return $data;
  }

  protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $perangkat = $record->perangkat;

        if (!$perangkat) return;

        $alasan = $record->alasan_penarikan ?? [];
        $newStatusId = null;

        $alasanLower = array_map('strtolower', $alasan);
        
        if (in_array('tidak layak pakai', $alasanLower) || in_array('melebihi masa pakai', $alasanLower)) {
            
            $status = Status::firstOrCreate(
                ['nama_status' => 'Sudah tidak digunakan'] 
            );
            
            $newStatusId = $status->id;
        } 
        elseif (in_array('rusak', $alasanLower)) {
            
            $status = Status::firstOrCreate(
                ['nama_status' => 'Rusak']
            );
            
            $newStatusId = $status->id;
        }

        if ($newStatusId) {
            $perangkat->status_id = $newStatusId;
            $perangkat->save();

            // Opsional: Kirim notifikasi agar user tahu sistem membuat status baru/update
            // Notification::make()
            //     ->title('Status Perangkat Diupdate')
            //     ->body("Perangkat kini berstatus: " . $status->nama_status)
            //     ->success()
            //     ->send();
        }
    }

  protected function getRedirectUrl(): string
  {
    $record = $this->record;

    if ($record->tindak_lanjut_tipe === 'Pindahan') {

      \Filament\Notifications\Notification::make()
        ->title('Penarikan Alat Berhasil Disimpan')
        ->body('Sekarang, silakan catat mutasi untuk perangkat pengganti.')
        ->success()
        ->send();

      return \App\Filament\Resources\Mutasis\MutasiResource::getUrl('create');
    }

    return $this->getResource()::getUrl('index');
  }
}
