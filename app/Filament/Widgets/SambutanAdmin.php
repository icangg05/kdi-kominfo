<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Beritas\BeritaResource;
use App\Filament\Resources\Dokumens\DokumenResource;
use App\Filament\Resources\Galeris\GaleriResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Support\WaktuWita;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

/** Pita sambutan di atas dasbor: sapaan WITA dan pintasan ke pekerjaan yang paling sering. */
class SambutanAdmin extends Widget
{
    protected string $view = 'filament.widgets.sambutan-admin';

    protected static ?int $sort = -10;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $sekarang = WaktuWita::sekarang();

        return [
            'sapaan' => WaktuWita::sapaan($sekarang),
            'nama' => auth()->user()?->name,
            'tanggal' => $sekarang->translatedFormat('l, j F Y'),
            'pintasan' => [
                ['Tulis berita', BeritaResource::getUrl('create'), Heroicon::OutlinedPencilSquare],
                ['Unggah dokumen', DokumenResource::getUrl('create'), Heroicon::OutlinedDocumentArrowUp],
                ['Tambah foto', GaleriResource::getUrl('create'), Heroicon::OutlinedPhoto],
                ['Tambah video', VideoResource::getUrl('create'), Heroicon::OutlinedVideoCamera],
            ],
        ];
    }
}
