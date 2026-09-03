<?php

namespace App\Filament\Resources\KategoriBeritas;

use App\Filament\Resources\KategoriBeritas\Pages\CreateKategoriBerita;
use App\Filament\Resources\KategoriBeritas\Pages\EditKategoriBerita;
use App\Filament\Resources\KategoriBeritas\Pages\ListKategoriBeritas;
use App\Filament\Resources\KategoriBeritas\Schemas\KategoriBeritaForm;
use App\Filament\Resources\KategoriBeritas\Tables\KategoriBeritasTable;
use App\Models\KategoriBerita;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KategoriBeritaResource extends Resource
{
    protected static ?string $model = KategoriBerita::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kategori Berita';

    protected static ?string $pluralModelLabel = 'Kategori Berita';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KategoriBeritaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriBeritasTable::configure($table);
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
            'index' => ListKategoriBeritas::route('/'),
            'create' => CreateKategoriBerita::route('/create'),
            'edit' => EditKategoriBerita::route('/{record}/edit'),
        ];
    }
}
