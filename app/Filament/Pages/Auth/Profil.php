<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Halaman profil bawaan Filament (menu akun di kanan atas), dipecah jadi dua kartu
 * seperti halaman Pengaturan Situs. Validasi dan penyimpanan tetap milik Filament.
 */
class Profil extends EditProfile
{
    protected static ?string $slug = 'profil';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel(false)
            ->components([
                Section::make('Data profil')
                    ->description('Email dipakai untuk masuk ke panel admin.')
                    ->columns(2)
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                    ]),
                Section::make('Ganti kata sandi')
                    ->description('Isi ketiganya untuk mengganti kata sandi. Kosongkan bila tidak ingin mengganti.')
                    ->columns(2)
                    ->schema([
                        // Bawaan Filament menyembunyikan kolom ini sampai kata sandi baru diisi; di sini selalu tampil.
                        // Tetap wajib juga saat email diganti.
                        $this->getCurrentPasswordFormComponent()
                            ->label('Kata sandi lama')
                            ->belowContent(null)
                            ->visible()
                            ->required(fn (Get $get): bool => filled($get('password')) || filled($get('passwordConfirmation'))
                                || $get('email') !== $this->getUser()->getAttributeValue('email'))
                            ->columnSpanFull(),
                        $this->getPasswordFormComponent()
                            ->required(fn (Get $get): bool => filled($get('passwordConfirmation'))),
                        $this->getPasswordConfirmationFormComponent()
                            ->visible()
                            ->required(fn (Get $get): bool => filled($get('password'))),
                    ]),
            ]);
    }
}
