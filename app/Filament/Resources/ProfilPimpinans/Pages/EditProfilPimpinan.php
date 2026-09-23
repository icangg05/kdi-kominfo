<?php

namespace App\Filament\Resources\ProfilPimpinans\Pages;

use App\Filament\Resources\ProfilPimpinans\ProfilPimpinanResource;
use App\Models\ProfilPimpinan;
use Filament\Resources\Pages\EditRecord;

/** Pimpinan hanya satu, jadi menu langsung membuka form-nya tanpa halaman daftar, tambah, atau hapus. */
class EditProfilPimpinan extends EditRecord
{
    protected static string $resource = ProfilPimpinanResource::class;

    protected static ?string $title = 'Profil Pimpinan';

    public function mount(int|string|null $record = null): void
    {
        parent::mount(ProfilPimpinan::firstOrCreate([], ['konten' => ''])->getKey());
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    // Tombol batal hanya kembali ke halaman ini sendiri.
    protected function getFormActions(): array
    {
        return [$this->getSaveFormAction()];
    }
}
