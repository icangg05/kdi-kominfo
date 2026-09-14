<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Login admin dengan tata letak instansi (panel merek biru + formulir),
 * bukan kartu tengah bawaan Filament. Logika autentikasi tetap milik Filament.
 */
class Masuk extends Login
{
    protected string $view = 'filament.pages.auth.masuk';

    protected static string $layout = 'filament.pages.auth.masuk-layout';

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()->placeholder('nama@kendarikota.go.id');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()->placeholder('Masukkan kata sandi');
    }

    public function getHeading(): string | Htmlable | null
    {
        return filled($this->userUndertakingMultiFactorAuthentication)
            ? parent::getHeading()
            : 'Masuk ke Panel Admin';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return filled($this->userUndertakingMultiFactorAuthentication)
            ? parent::getSubheading()
            : 'Gunakan email dan kata sandi akun admin Anda.';
    }
}
