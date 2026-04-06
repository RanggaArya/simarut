<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User as AppUser;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // NOTE: Ini harusnya "mutateFormDataBeforeSave" (Edit), bukan BeforeCreate.
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $auth = Auth::user();

        if (! ($auth instanceof AppUser)) {
            return $data;
        }

        if ($auth->isAdmin() && ! $auth->isSuperAdmin()) {
            $data['role'] = 'user';
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
