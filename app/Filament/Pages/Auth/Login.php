<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.login';
    protected static ?string $title = 'Login | Alkes';

    protected function getLayoutData(): array
    {
        return [
            'pageTitle' => static::$title,
        ];
    }

    public function getLayout(): string
    {
        return 'filament.pages.layout';
    }
}