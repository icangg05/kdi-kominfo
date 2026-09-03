<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
                FileUpload::make('gambar')
                    ->required()
                    ->image()
                    ->disk('public')
                    ->directory('galeri')
                    ->imageEditor()
                    ->maxSize(4096)
                    ->columnSpanFull(),
            ]);
    }
}
