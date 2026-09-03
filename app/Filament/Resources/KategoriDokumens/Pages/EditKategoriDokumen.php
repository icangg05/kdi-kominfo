<?php

namespace App\Filament\Resources\KategoriDokumens\Pages;

use App\Filament\Resources\KategoriDokumens\KategoriDokumenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriDokumen extends EditRecord
{
    protected static string $resource = KategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
