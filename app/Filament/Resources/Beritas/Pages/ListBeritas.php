<?php

namespace App\Filament\Resources\Beritas\Pages;

use App\Filament\Resources\Beritas\BeritaResource;
use App\Models\ProfilDinas;
use App\Support\SinkronBerita;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Throwable;

class ListBeritas extends ListRecords
{
    protected static string $resource = BeritaResource::class;

    public function getSubheading(): ?Htmlable
    {
        if (! SinkronBerita::atur()['aktif']) {
            return null;
        }

        $status = ProfilDinas::konten(SinkronBerita::STATUS);

        $terakhir = $status
            ? Carbon::parse($status['dijalankan'])->translatedFormat('d M Y, H.i') . ' WITA'
            : 'belum pernah';

        $berikutnya = SinkronBerita::berikutnya();

        return new HtmlString(
            '<span class="text-xs">Portal berita kota · Sinkron otomatis terakhir: <strong>' . e($terakhir)
            . '</strong> · Berikutnya: <strong>' . e($berikutnya->translatedFormat('d M Y, H.i') . ' WITA')
            . '</strong> (' . e($berikutnya->diffForHumans()) . ')</span>',
        );
    }

    /** Tautan ke pencarian yang sama di portal aslinya, supaya admin bisa mengecek hasilnya langsung. */
    private static function tautanPratinjau(?string $kata): ?Htmlable
    {
        if (blank($kata)) {
            return null;
        }

        $url = config('app.berita_wp.url') . '/?s=' . urlencode($kata);

        return new HtmlString(
            'Lihat hasilnya di portal: <a href="' . e($url) . '" target="_blank" rel="noopener noreferrer" class="underline">' . e($url) . '</a>',
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            // Sengaja tanpa visible(): kalau ikut `aktif`, admin yang mematikan sinkron
            // tidak punya jalan untuk menyalakannya lagi.
            Action::make('aturSinkron')
                ->label('Pengaturan sinkron')
                ->icon(Heroicon::OutlinedCog6Tooth)
                ->color('gray')
                ->fillForm(fn (): array => SinkronBerita::atur())
                ->schema([
                    Repeater::make('kata_kunci')
                        ->label('Kata kunci pencarian')
                        ->helperText('Tiap kata kunci jadi satu pencarian ke portal berita kota; hasilnya digabung dan berita yang sama tidak diambil dua kali.')
                        ->simple(
                            TextInput::make('value')
                                ->required()
                                ->maxLength(100)
                                // Debounce, bukan onBlur: tautan pratinjau ikut berubah sambil
                                // mengetik, tapi tidak bolak-balik ke server tiap ketukan.
                                ->live(debounce: 500)
                                ->helperText(fn (?string $state): ?Htmlable => self::tautanPratinjau($state)),
                        )
                        ->addActionLabel('Tambah kata kunci')
                        ->reorderable()
                        ->minItems(1),
                    TextInput::make('maks_per_sinkron')
                        ->label('Maksimal berita per sinkron')
                        // Dibatasi 20: sampul diunduh saat sinkron, dan lebih dari itu
                        // berisiko menembus batas waktu 30 detik milik request admin.
                        ->helperText('Isi 1 sampai 20 berita.')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(20)
                        ->required(),
                    Toggle::make('aktif')
                        ->label('Sinkron otomatis tiap 6 jam (03.00, 09.00, 15.00, 21.00 WITA)'),
                ])
                ->action(function (array $data): void {
                    ProfilDinas::updateOrCreate(['jenis' => SinkronBerita::ATUR], ['konten' => [
                        'kata_kunci' => $data['kata_kunci'],
                        'maks_per_sinkron' => (int) $data['maks_per_sinkron'],
                        'aktif' => (bool) $data['aktif'],
                    ]]);

                    Notification::make()->success()->title('Pengaturan sinkron tersimpan')->send();
                }),
            Action::make('sinkron')
                ->label('Ambil berita sekarang')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->visible(fn (): bool => (bool) SinkronBerita::atur()['aktif'])
                ->action(function (): void {
                    try {
                        $baru = SinkronBerita::jalankan(otomatis: false);
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
