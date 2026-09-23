<?php

namespace App\Filament\Resources\Pegawais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PegawaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                // Ukuran persegi supaya foto hasil crop bebas dan gambar default tetap bulat; file hilang jatuh ke default.
                ImageColumn::make('foto')->disk('public')->circular()->imageSize(32)->label('Foto')->defaultImageUrl('/img/gambar-default.webp'),
                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('nip')->label('NIP')->searchable()->color('gray')->placeholder('-'),
                TextColumn::make('jabatan.nama')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama')
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
