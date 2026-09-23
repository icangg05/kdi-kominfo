<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Models\Video;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->placeholder('Contoh: Sosialisasi Layanan Smart City Kota Kendari')
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
                    // Pratinjau di bawah ikut diperbarui 0,5 detik setelah berhenti mengetik/menempel.
                    ->live(debounce: 500)
                    ->columnSpanFull(),
                Html::make(function (Get $get): string {
                    // Parser ID yang sama dengan API situs; ID-nya cuma [\w-]{11}, aman ditempel ke HTML.
                    $tautan = trim((string) $get('tautan'));
                    $id = (new Video(['tautan' => $tautan]))->youtubeId();

                    return match (true) {
                        $id !== null => '<iframe src="https://www.youtube-nocookie.com/embed/' . $id . '?rel=0" title="Pratinjau video" loading="lazy" allowfullscreen'
                            . ' allow="accelerometer; encrypted-media; picture-in-picture"'
                            . ' style="display:block;width:100%;max-width:640px;aspect-ratio:16/9;border:0;border-radius:0.25rem"></iframe>',
                        $tautan === '' => '<p class="kdi-ket-pratinjau">Pratinjau video tampil di sini setelah tautan YouTube diisi.</p>',
                        default => '<p role="alert" class="kdi-ket-pratinjau kdi-galat">Tautan YouTube tidak valid, pratinjau tidak bisa ditampilkan. Pastikan tautan lengkap, misalnya https://www.youtube.com/watch?v=… atau https://youtu.be/…</p>',
                    };
                })->columnSpanFull(),
            ]);
    }
}
