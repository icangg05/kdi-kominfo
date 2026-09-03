<?php

namespace App\Filament\Resources\KategoriDokumens;

use App\Filament\Resources\KategoriDokumens\Pages\CreateKategoriDokumen;
use App\Filament\Resources\KategoriDokumens\Pages\EditKategoriDokumen;
use App\Filament\Resources\KategoriDokumens\Pages\ListKategoriDokumens;
use App\Filament\Resources\KategoriDokumens\Schemas\KategoriDokumenForm;
use App\Filament\Resources\KategoriDokumens\Tables\KategoriDokumensTable;
use App\Models\KategoriDokumen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KategoriDokumenResource extends Resource
{
    protected static ?string $model = KategoriDokumen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Kategori Dokumen';

    protected static ?string $pluralModelLabel = 'Kategori Dokumen';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KategoriDokumenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriDokumensTable::configure($table);
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
            'index' => ListKategoriDokumens::route('/'),
            'create' => CreateKategoriDokumen::route('/create'),
            'edit' => EditKategoriDokumen::route('/{record}/edit'),
        ];
    }
}
