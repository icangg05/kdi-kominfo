<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoForm
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
                TextInput::make('tautan')
                    ->label('Tautan YouTube')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->regex('~^https?://(www\.|m\.)?(youtube\.com/(watch\?|embed/|shorts/|live/)|youtu\.be/)~')
                    ->validationMessages(['regex' => 'Gunakan tautan video YouTube, misalnya https://www.youtube.com/watch?v=...'])
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->columnSpanFull(),
            ]);
    }
}
