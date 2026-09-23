<?php

namespace App\Filament\Resources\Dokumens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DokumensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                TextColumn::make('judul')->searchable()->limit(70)->tooltip(fn ($record): string => $record->judul),
                TextColumn::make('kategori.nama')->badge()->sortable(),
                TextColumn::make('total_unduhan')->label('Diunduh')->numeric()->sortable(),
                TextColumn::make('created_at')->label('Ditambahkan')->date('d M Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori_dokumen_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
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
