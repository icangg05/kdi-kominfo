<?php

namespace App\Filament\Resources\Galeris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalerisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                // Filament sudah mengecek file di disk; yang hilang atau kosong jatuh ke gambar default situs.
                ImageColumn::make('gambar')->disk('public')->label('Gambar')->defaultImageUrl('/img/gambar-default.webp'),
                TextColumn::make('judul')->searchable()->limit(70)->tooltip(fn ($record): string => $record->judul),
                TextColumn::make('tanggal')->date('d M Y')->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
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
