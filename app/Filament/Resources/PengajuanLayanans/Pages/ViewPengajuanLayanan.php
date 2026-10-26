<?php

namespace App\Filament\Resources\PengajuanLayanans\Pages;

use App\Filament\Resources\PengajuanLayanans\PengajuanLayananAksi;
use App\Filament\Resources\PengajuanLayanans\PengajuanLayananResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPengajuanLayanan extends ViewRecord
{
    protected static string $resource = PengajuanLayananResource::class;

    public function getTitle(): string
    {
        return "Pengajuan {$this->getRecord()->nomor}";
    }

    public function getSubheading(): ?string
    {
        return $this->getRecord()->labelLayanan() . ' · ' . $this->getRecord()->instansi;
    }

    protected function getHeaderActions(): array
    {
        return [
            ...PengajuanLayananAksi::semua(),
            ActionGroup::make([
                DeleteAction::make()
                    ->modalDescription('Pengajuan beserta suratnya dihapus permanen. Gunakan hanya untuk kiriman palsu atau ganda.'),
            ]),
        ];
    }
}
