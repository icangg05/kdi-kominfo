<?php

namespace App\Filament\Resources\Beritas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BeritaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state)))
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Dipakai di alamat /berita/{slug}. Mengubahnya memutus tautan lama.'),
                Select::make('kategori_berita_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
                FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('berita')
                    ->imageEditor()
                    ->maxSize(4096),
                RichEditor::make('konten')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
