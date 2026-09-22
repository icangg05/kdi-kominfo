<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use App\Support\KompresGambar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nip')
                    ->label('NIP')
                    ->maxLength(255),
                Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('nama')->required()->maxLength(255),
                    ]),
                DatePicker::make('tanggal_lahir'),
                FileUpload::make('foto')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('pegawai')
                    ->imageEditor()
                    ->maxSize(config('app.upload.gambar_maks_kb'))
                    ->saveUploadedFileUsing(KompresGambar::simpan(...)),
                Textarea::make('alamat')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
