<?php

namespace App\Filament\Resources\Beritas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BeritasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                TextColumn::make('judul')->searchable()->limit(70)->tooltip(fn ($record): string => $record->judul),
                TextColumn::make('kategori.nama')->label('Kategori')->badge()->sortable(),
                TextColumn::make('wp_id')
                    ->label('Sumber')
                    ->formatStateUsing(fn (): string => 'Portal kota')
                    ->placeholder('Manual')
                    ->url(fn ($record): ?string => $record->wp_url, shouldOpenInNewTab: true),
                TextColumn::make('tanggal')->date('d M Y')->sortable(),
                TextColumn::make('total_lihat')->label('Dilihat')->numeric()->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori_berita_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->preload(),
                TernaryFilter::make('wp_id')
                    ->label('Sumber')
                    ->nullable()
                    ->trueLabel('Portal berita kota')
                    ->falseLabel('Input manual'),
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
