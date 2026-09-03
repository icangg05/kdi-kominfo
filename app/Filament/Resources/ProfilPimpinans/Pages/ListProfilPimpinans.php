<?php

namespace App\Filament\Resources\ProfilPimpinans\Pages;

use App\Filament\Resources\ProfilPimpinans\ProfilPimpinanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProfilPimpinans extends ListRecords
{
    protected static string $resource = ProfilPimpinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
