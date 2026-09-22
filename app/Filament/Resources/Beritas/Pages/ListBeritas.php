<?php

namespace App\Filament\Resources\Beritas\Pages;

use App\Filament\Resources\Beritas\BeritaResource;
use App\Models\ProfilDinas;
use App\Support\SinkronBerita;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Throwable;

class ListBeritas extends ListRecords
{
    protected static string $resource = BeritaResource::class;

    public function getSubheading(): ?string
    {
        if (! config('app.berita_wp.aktif')) {
            return null;
        }

        $status = ProfilDinas::konten(SinkronBerita::STATUS);

        return $status
            ? 'Sinkron terakhir dengan portal berita kota: ' . Carbon::parse($status['dijalankan'])->translatedFormat('d M Y, H.i') . ' WITA. Otomatis setiap 6 jam.'
            : 'Belum pernah sinkron dengan portal berita kota. Otomatis setiap 6 jam.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sinkron')
                ->label('Ambil berita sekarang')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->visible(fn (): bool => (bool) config('app.berita_wp.aktif'))
                ->action(function (): void {
                    try {
                        $baru = SinkronBerita::jalankan();
                    } catch (Throwable $e) {
                        report($e);
                        Notification::make()->danger()->title('Gagal mengambil berita')->body('Portal berita kota tidak bisa dihubungi. Coba lagi nanti.')->send();

                        return;
                    }

                    match ($baru) {
                        null => Notification::make()->warning()->title('Sinkronisasi sedang berjalan')->body('Tunggu sebentar lalu muat ulang halaman.')->send(),
                        0 => Notification::make()->success()->title('Tidak ada berita baru')->send(),
                        default => Notification::make()->success()->title("{$baru} berita baru diambil")->send(),
                    };
                }),
            CreateAction::make(),
        ];
    }
}
