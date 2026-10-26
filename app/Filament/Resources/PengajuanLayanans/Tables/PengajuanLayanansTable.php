<?php

namespace App\Filament\Resources\PengajuanLayanans\Tables;

use App\Filament\Resources\PengajuanLayanans\PengajuanLayananAksi;
use App\Models\PengajuanLayanan;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengajuanLayanansTable
{
    public const WARNA_STATUS = [
        'menunggu' => 'warning',
        'diproses' => 'info',
        'disetujui' => 'success',
        'ditolak' => 'danger',
    ];

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor disalin')
                    ->fontFamily('mono')
                    ->weight('semibold'),
                TextColumn::make('layanan')
                    ->badge()
                    ->wrap()
                    ->formatStateUsing(fn (string $state): string => PengajuanLayanan::JENIS[$state]['label'] ?? $state),
                // Teks panjang dibungkus, bukan dipotong: tanpa itu tabel melebar dan tombol aksi tergeser keluar layar.
                TextColumn::make('instansi')
                    ->label('Perangkat daerah')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('nama_pemohon')
                    ->label('Pemohon')
                    ->searchable()
                    ->wrap()
                    ->description(fn (PengajuanLayanan $record): string => $record->whatsapp),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y, H.i')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => PengajuanLayanan::STATUS[$state] ?? $state)
                    ->color(fn (string $state): string => self::WARNA_STATUS[$state] ?? 'gray'),
            ])
            ->filters([
                SelectFilter::make('layanan')
                    ->options(collect(PengajuanLayanan::JENIS)->map(fn ($j) => $j['label'])->all()),
                SelectFilter::make('status')
                    ->options(PengajuanLayanan::STATUS),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (PengajuanLayanan $record): string => route('filament.admin.resources.pengajuan-layanan.view', $record))
            ->recordActions([
                ViewAction::make()->label('Periksa'),
                ActionGroup::make([
                    ...PengajuanLayananAksi::semua(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pengajuan')
            ->emptyStateDescription('Pengajuan dari formulir di halaman layanan situs akan muncul di sini.');
    }
}
