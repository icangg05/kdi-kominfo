<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
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

    /**
     * Bawaan Filament 5 percobaan per menit per IP; di sini diperketat jadi 4. Kunci terpisah ('masuk'),
     * jadi pembatas bawaan di parent tidak pernah tercapai lebih dulu.
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(4, method: 'masuk');
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        return parent::authenticate();
    }

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
