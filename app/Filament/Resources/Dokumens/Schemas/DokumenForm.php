<?php

namespace App\Filament\Resources\Dokumens\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DokumenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->placeholder('Contoh: Rencana Strategis Diskominfo Kota Kendari 2025-2029')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('kategori_dokumen_id')
                    ->label('Kategori')
                    ->placeholder('Pilih kategori')
                    ->relationship('kategori', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                FileUpload::make('file')
                    ->required()
                    ->disk('public')
                    ->directory('dokumen')
                    ->placeholder('Seret dokumen ke sini atau klik untuk memilih')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->maxSize(config('app.upload.file_maks_kb'))
                    ->helperText('PDF, Word, atau Excel. Maksimal ' . (config('app.upload.file_maks_kb') / 1024) . ' MB.'),
                Textarea::make('deskripsi')
                    ->placeholder('Ringkasan singkat isi dokumen, misalnya tujuan dan periode berlakunya')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
