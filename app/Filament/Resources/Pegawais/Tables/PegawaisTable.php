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
                ImageColumn::make('foto')->disk('public')->circular()->label('Foto'),
                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('nip')->label('NIP')->searchable()->color('gray'),
                TextColumn::make('jabatan.nama')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama')
                    ->preload(),
            ])
            ->defaultSort('nama')
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
