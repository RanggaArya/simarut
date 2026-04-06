<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput; 
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static ?string $title = 'Pengaturan Akun';
    protected static ?string $slug = 'account-settings';
    protected string $view = 'filament.pages.account-settings';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }
    
    protected function fillForm(): void
    {
         $user = Auth::user();
         $this->data = [
            'name'    => $user->name,
            'email'   => $user->email,
            'jabatan' => $user->jabatan,
            'unit'    => $user->unit,
            'current_password' => '', 
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Konfirmasi Keamanan')
                    ->description('Masukkan password lama Anda untuk melakukan perubahan data.')
                    ->aside()
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Lama (Wajib Diisi)')
                            ->password()
                            ->revealable()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Profil Pengguna')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email(),

                        TextInput::make('jabatan')
                            ->label('Jabatan'),

                        TextInput::make('unit')
                            ->label('Unit Kerja'),
                    ])->columns(2),

                Section::make('Ganti Password Baru')
                    ->description('Isi bagian ini HANYA jika ingin mengganti password.')
                    ->aside()
                    ->schema([
                        TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->minLength(8),

                        TextInput::make('new_password_confirmation')
                            ->label('Ulangi Password Baru')
                            ->password()
                            ->revealable()
                            ->same('new_password'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }
    
    protected function getSchemas(): array 
    {
        return [
            'form' => $this->form(Schema::make($this)),
        ];
    }

    public function save(): void
    {
        $data = $this->getSchema('form')->getState();
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title('Gagal Disimpan')
                ->body('Password lama yang Anda masukkan SALAH.')
                ->danger()
                ->send();
                
            return;
        }

        $emailChanged = $data['email'] !== $user->email;
        $passwordChanged = !empty($data['new_password']);

        $user->name    = $data['name'];
        $user->email   = $data['email'];
        $user->jabatan = $data['jabatan'] ?? null;
        $user->unit    = $data['unit'] ?? null;

        if ($passwordChanged) {
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        if ($emailChanged || $passwordChanged) {
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();

            $this->redirect('/admin/login'); 
            return;
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Data profil berhasil diperbarui.')
            ->success()
            ->send();
            
        $this->data['current_password'] = null;
        $this->data['new_password'] = null;
        $this->data['new_password_confirmation'] = null;
    }
}