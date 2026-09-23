<?php

namespace App\Filament\Resources\Beritas\Schemas;

use App\Support\KompresGambar;
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
                    ->placeholder('Contoh: Diskominfo Kendari Gelar Pelatihan Literasi Digital')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state)))
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->placeholder('Terisi otomatis dari judul')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Dipakai di alamat /berita/{slug}. Mengubahnya memutus tautan lama.'),
                Select::make('kategori_berita_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->placeholder('Pilih kategori')
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
                    ->maxSize(config('app.upload.gambar_maks_kb'))
                    ->saveUploadedFileUsing(KompresGambar::simpan(...))
                    ->helperText('Maksimal ' . (config('app.upload.gambar_maks_kb') / 1024) . ' MB.'),
                RichEditor::make('konten')
                    ->placeholder('Tulis isi berita di sini...')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
