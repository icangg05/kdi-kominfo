<?php

namespace App\Filament\Resources\ProfilPimpinans;

use App\Filament\Resources\ProfilPimpinans\Pages\CreateProfilPimpinan;
use App\Filament\Resources\ProfilPimpinans\Pages\EditProfilPimpinan;
use App\Filament\Resources\ProfilPimpinans\Pages\ListProfilPimpinans;
use App\Filament\Resources\ProfilPimpinans\Schemas\ProfilPimpinanForm;
use App\Filament\Resources\ProfilPimpinans\Tables\ProfilPimpinansTable;
use App\Models\ProfilPimpinan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfilPimpinanResource extends Resource
{
    protected static ?string $model = ProfilPimpinan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil Dinas';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Profil Pimpinan';

    protected static ?string $pluralModelLabel = 'Profil Pimpinan';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return ProfilPimpinanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfilPimpinansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProfilPimpinans::route('/'),
            'create' => CreateProfilPimpinan::route('/create'),
            'edit' => EditProfilPimpinan::route('/{record}/edit'),
        ];
    }
}
