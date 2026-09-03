<?php

namespace App\Filament\Resources\Beritas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BeritasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')->disk('public')->label('Gambar'),
                TextColumn::make('judul')->searchable()->limit(60)->wrap(),
                TextColumn::make('kategori.nama')->badge()->sortable(),
                TextColumn::make('tanggal')->date('d M Y')->sortable(),
                TextColumn::make('total_lihat')->label('Dilihat')->numeric()->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori_berita_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->preload(),
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
