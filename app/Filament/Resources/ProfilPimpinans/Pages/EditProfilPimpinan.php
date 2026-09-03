<?php

namespace App\Filament\Resources\ProfilPimpinans\Pages;

use App\Filament\Resources\ProfilPimpinans\ProfilPimpinanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProfilPimpinan extends EditRecord
{
    protected static string $resource = ProfilPimpinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
