<?php

namespace App\Support;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\ProfilDinas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Ambil berita terkait Kominfo dari WordPress portal berita kota; pengaturan di config('app.berita_wp'). */
final class SinkronBerita
{
    public const STATUS = 'sinkron-berita';

    /** Jumlah berita baru, atau null bila sinkronisasi lain masih berjalan. */
    public static function jalankan(): ?int
    {
        $hasil = Cache::lock('sinkron-berita', 600)->get(function (): int {
            $atur = config('app.berita_wp');
            $status = ProfilDinas::konten(self::STATUS) ?: [];

            // Sinkron pertama: berita terbaru. Berikutnya: lanjut dari berita terakhir yang diambil, urut
            // terlama dulu supaya tumpukan yang melebihi batas tetap terambil di sinkron selanjutnya.
            // Karena hanya maju dari titik ini, berita yang dihapus di admin tidak ikut diambil ulang.
            $setelah = $status['terakhir'] ?? null;

            $pos = self::cari($atur, $setelah)
                ->unique('id')
                ->sortBy('date', descending: $setelah === null)
                ->take($atur['maks_per_sinkron']);

            $kategori = KategoriBerita::firstOrCreate(['slug' => Str::slug($atur['kategori'])], ['nama' => $atur['kategori']]);
            $baru = 0;

            foreach ($pos as $p) {
                if (! Berita::where('wp_id', $p['id'])->exists()) {
                    self::simpan($p, $kategori->id);
                    $baru++;
                }
            }

            ProfilDinas::updateOrCreate(['jenis' => self::STATUS], ['konten' => [
                'terakhir' => $pos->max('date') ?? $setelah,
                'dijalankan' => now()->toIso8601String(),
                'baru' => $baru,
            ]]);

            return $baru;
        });

        return $hasil === false ? null : $hasil;
    }

    private static function cari(array $atur, ?string $setelah): Collection
    {
        return collect($atur['kata_kunci'])->flatMap(fn (string $kata) => Http::baseUrl($atur['url'])
            ->timeout(20)
            ->retry(2, 1000)
            ->get('/wp-json/wp/v2/posts', array_filter([
                'search' => $kata,
                'after' => $setelah,
                'order' => $setelah ? 'asc' : 'desc',
                'per_page' => $atur['maks_per_sinkron'],
                '_embed' => 'wp:featuredmedia',
                '_fields' => 'id,date,link,title,content,_links,_embedded',
            ]))
            ->throw()
            ->json());
    }

    private static function simpan(array $p, int $kategoriId): void
    {
        $judul = html_entity_decode(strip_tags($p['title']['rendered']), ENT_QUOTES | ENT_HTML5);
        $slug = Str::limit(Str::slug($judul), 200, '') ?: "berita-{$p['id']}";

        Berita::create([
            'wp_id' => $p['id'],
            'wp_url' => $p['link'],
            'kategori_berita_id' => $kategoriId,
            'judul' => Str::limit($judul, 250, ''),
            'slug' => Berita::where('slug', $slug)->exists() ? "{$slug}-{$p['id']}" : $slug,
            // Konten dari situs luar tampil sebagai HTML di situs kita, jadi skrip dan atribut berbahaya dibuang.
            'konten' => Str::sanitizeHtml($p['content']['rendered']),
            'tanggal' => Carbon::parse($p['date'])->toDateString(),
            'thumbnail' => self::unduhSampul($p),
        ]);
    }

    /** Gambar unggulan disalin ke disk sendiri sebagai WebP; gagal apa pun berarti tanpa sampul (gambar default). */
    private static function unduhSampul(array $p): ?string
    {
        $media = $p['_embedded']['wp:featuredmedia'][0] ?? [];
        $url = $media['media_details']['sizes']['large']['source_url'] ?? $media['source_url'] ?? null;

        // Hanya dari host portal itu sendiri, supaya respons API tidak bisa menyuruh server mengunduh alamat lain.
        if (! $url || parse_url($url, PHP_URL_HOST) !== parse_url(config('app.berita_wp.url'), PHP_URL_HOST)) {
            return null;
        }

        $isi = rescue(fn () => Http::timeout(20)->get($url)->throw()->body(), report: false);
        $kompres = config('app.upload.kompres_gambar');
        $webp = $isi ? KompresGambar::keWebp($isi, $kompres['lebar_maks'], $kompres['kualitas']) : null;

        if ($webp === null) {
            return null;
        }

        $path = 'berita/' . Str::ulid() . '.webp';
        Storage::disk('public')->put($path, $webp);

        return $path;
    }
}
