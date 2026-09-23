<?php

namespace App\Filament\Resources\ProfilPimpinans;

use App\Filament\Resources\ProfilPimpinans\Pages\EditProfilPimpinan;
use App\Filament\Resources\ProfilPimpinans\Schemas\ProfilPimpinanForm;
use App\Models\ProfilPimpinan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProfilPimpinanResource extends Resource
{
    protected static ?string $model = ProfilPimpinan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil Dinas';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Profil Pimpinan';

    protected static ?string $pluralModelLabel = 'Profil Pimpinan';

    public static function form(Schema $schema): Schema
    {
        return ProfilPimpinanForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditProfilPimpinan::route('/'),
        ];
    }
}
