<?php

namespace App\Filament\Resources\PengajuanLayanans;

use App\Filament\Resources\PengajuanLayanans\Pages\ListPengajuanLayanans;
use App\Filament\Resources\PengajuanLayanans\Pages\ViewPengajuanLayanan;
use App\Filament\Resources\PengajuanLayanans\Schemas\PengajuanLayananInfolist;
use App\Filament\Resources\PengajuanLayanans\Tables\PengajuanLayanansTable;
use App\Models\PengajuanLayanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Verifikasi pengajuan layanan dari formulir situs publik. Admin tidak membuat atau mengubah isian
 * pemohon; yang diubah hanya status dan catatan (lihat PengajuanLayananAksi).
 */
class PengajuanLayananResource extends Resource
{
    protected static ?string $model = PengajuanLayanan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Pengajuan Layanan';

    protected static ?string $modelLabel = 'Pengajuan Layanan';

    protected static ?string $pluralModelLabel = 'Pengajuan Layanan';

    protected static ?string $recordTitleAttribute = 'nomor';

    protected static ?string $slug = 'pengajuan-layanan';

    public static function canCreate(): bool
    {
        return false;
    }

    /** Jumlah yang belum disentuh petugas, supaya terlihat dari menu mana pun. */
    public static function getNavigationBadge(): ?string
    {
        $menunggu = PengajuanLayanan::where('status', 'menunggu')->count();

        return $menunggu > 0 ? (string) $menunggu : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): string
    {
        return 'Menunggu verifikasi';
    }

    public static function infolist(Schema $schema): Schema
    {
        return PengajuanLayananInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengajuanLayanansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPengajuanLayanans::route('/'),
            'view' => ViewPengajuanLayanan::route('/{record}'),
        ];
    }
}
