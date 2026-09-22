<?php

namespace App\Filament\Resources\ProfilPimpinans\Schemas;

use App\Support\KompresGambar;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProfilPimpinanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('awal_periode')
                    ->label('Awal periode')
                    ->required()
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100),
                TextInput::make('akhir_periode')
                    ->label('Akhir periode')
                    ->required()
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100),
                FileUpload::make('foto')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->disk('public')
                    ->directory('foto-kadis')
                    ->maxSize(config('app.upload.gambar_maks_kb'))
                    ->saveUploadedFileUsing(KompresGambar::simpan(...))
                    ->columnSpanFull()
                    ->helperText('Foto pertama dipakai sebagai foto utama di beranda.'),
                RichEditor::make('konten')
                    ->label('Profil')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
