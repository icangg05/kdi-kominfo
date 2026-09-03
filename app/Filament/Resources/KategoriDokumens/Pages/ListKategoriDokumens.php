<?php

namespace App\Filament\Resources\KategoriDokumens\Pages;

use App\Filament\Resources\KategoriDokumens\KategoriDokumenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKategoriDokumens extends ListRecords
{
    protected static string $resource = KategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
