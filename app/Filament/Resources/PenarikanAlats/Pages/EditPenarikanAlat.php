<?php

namespace App\Filament\Resources\PenarikanAlats\Pages;

use App\Filament\Resources\PenarikanAlats\PenarikanAlatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\Status;
use Filament\Notifications\Notification;

class EditPenarikanAlat extends EditRecord
{
  protected static string $resource = PenarikanAlatResource::class;

  protected function getHeaderActions(): array
  {
    return [
      ViewAction::make(),
      DeleteAction::make(),
    ];
  }
  protected function afterCreate(): void
    {
        // 1. Ambil Record
        $record = $this->getRecord();
        $perangkat = $record->perangkat;

        if (!$perangkat) return;

        $alasan = $record->alasan_penarikan ?? [];
        $newStatusId = null;

        // Normalisasi alasan menjadi huruf kecil untuk pengecekan
        $alasanLower = array_map('strtolower', $alasan);

        // 2. Logika Penentuan Status dengan 'firstOrCreate'
        
        // KASUS 1: Tidak Layak / Melebihi Masa Pakai -> Target: "Sudah tidak digunakan"
        if (in_array('tidak layak pakai', $alasanLower) || in_array('melebihi masa pakai', $alasanLower)) {
            
            // "Cari status bernama 'Sudah tidak digunakan'. Jika belum ada, buatkan otomatis!"
            $status = Status::firstOrCreate(
                ['nama_status' => 'Sudah tidak digunakan'] 
            );
            
            $newStatusId = $status->id;
        } 
        // KASUS 2: Rusak -> Target: "Rusak"
        elseif (in_array('rusak', $alasanLower)) {
            
            // Kita cari 'Rusak'. Karena di DB Anda ada 'rusak' (kecil), 
            // MySQL biasanya case-insensitive (Rusak = rusak).
            // Tapi jika tidak ketemu, dia akan bikin 'Rusak' (Huruf Besar).
            $status = Status::firstOrCreate(
                ['nama_status' => 'Rusak']
            );
            
            $newStatusId = $status->id;
        }

        // 3. Update Perangkat
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
    return $this->getResource()::getUrl('index');
  }
}
