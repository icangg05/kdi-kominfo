<?php

namespace App\Support;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\ProfilDinas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Ambil berita terkait Kominfo dari WordPress portal berita kota; pengaturan lewat self::atur(). */
final class SinkronBerita
{
    public const STATUS = 'sinkron-berita';

    public const ATUR = 'sinkron-berita-atur';

    /** Jam sinkron otomatis (WITA), urut menaik. Tetap berjarak 6 jam. */
    public const JAM = [3, 9, 15, 21];

    /** Ekspresi cron untuk Schedule di routes/console.php. */
    public static function cron(): string
    {
        return '0 ' . implode(',', self::JAM) . ' * * *';
    }

    /** Jam sinkron otomatis terdekat sesudah sekarang. */
    public static function berikutnya(): Carbon
    {
        $sekarang = now();

        return collect(self::JAM)
            ->map(fn (int $jam): Carbon => $sekarang->copy()->setTime($jam, 0))
            ->first(fn (Carbon $waktu): bool => $waktu->isAfter($sekarang))
            ?? $sekarang->copy()->addDay()->setTime(self::JAM[0], 0);
    }

    /**
     * Setelan yang berlaku: config('app.berita_wp') sebagai nilai awal, ditimpa apa yang
     * disimpan admin. Dibungkus rescue supaya scheduler tetap jalan kalau tabelnya belum
     * ada (container baru, migrasi belum selesai).
     */
    public static function atur(): array
    {
        return array_merge(
            config('app.berita_wp'),
            rescue(fn () => ProfilDinas::konten(self::ATUR), [], report: false) ?: [],
        );
    }

    /**
     * Jumlah berita baru, atau null bila sinkronisasi lain masih berjalan.
     * `$otomatis` false (tombol admin) tidak mengubah catatan waktu sinkron otomatis.
     */
    public static function jalankan(bool $otomatis = true): ?int
    {
        // Kunci berumur pendek: kalau proses mati mendadak (mis. batas waktu PHP), tombol
        // admin tidak ikut terblokir lama karena lock-nya tidak sempat dilepas.
        $hasil = Cache::lock('sinkron-berita', 180)->get(function () use ($otomatis): int {
            $atur = self::atur();

            // Selalu ambil berita terbaru dari portal, tanpa penanda "sudah sampai mana".
            // Yang menentukan sebuah berita dilewati hanyalah wp_id-nya sudah ada di sini,
            // jadi berita yang dihapus admin akan muncul lagi saat ditarik ulang.
            $pos = self::cari($atur)
                ->unique('id')
                ->sortByDesc('date')
                ->take($atur['maks_per_sinkron']);

            $sudahAda = Berita::whereIn('wp_id', $pos->pluck('id'))->pluck('wp_id')->all();
            $perlu = $pos->reject(fn (array $p) => in_array($p['id'], $sudahAda))->values();

            // Sampul diunduh sekaligus, bukan satu per satu: berurutan ±1,25 detik per berita
            // membuat 20 berita menembus batas 30 detik milik request admin.
            $sampul = self::unduhSampul($perlu);

            foreach ($perlu as $p) {
                self::simpan($p, self::kategoriUntuk($p, $atur['kategori_cadangan']), $sampul[$p['id']] ?? null);
            }

            $baru = $perlu->count();

            // Hanya sinkron otomatis yang dicatat: tombol admin tidak boleh menggeser
            // keterangan "sinkron otomatis terakhir" di halaman Berita.
            if ($otomatis) {
                ProfilDinas::updateOrCreate(['jenis' => self::STATUS], ['konten' => [
                    'dijalankan' => now()->toIso8601String(),
                    'baru' => $baru,
                ]]);
            }

            return $baru;
        });

        return $hasil === false ? null : $hasil;
    }

    private static function cari(array $atur): Collection
    {
        return collect($atur['kata_kunci'])->flatMap(fn (string $kata) => Http::baseUrl($atur['url'])
            ->timeout(20)
            ->retry(2, 1000)
            ->get('/wp-json/wp/v2/posts', array_filter([
                'search' => $kata,
                // Hanya judul, supaya hasilnya sama persis dengan halaman pencarian portal
                // (/?s=kata). Tanpa ini API ikut mencari ke isi berita dan menarik berita
                // yang judulnya tidak menyebut kata kunci sama sekali.
                'search_columns' => 'post_title',
                'order' => 'desc',
                'per_page' => $atur['maks_per_sinkron'],
                // _links wajib ikut: tanpa itu WordPress mengembalikan _embedded kosong.
                '_embed' => 'wp:featuredmedia,wp:term',
                '_fields' => 'id,date,link,title,content,_links,_embedded',
            ]))
            ->throw()
            ->json());
    }

    /**
     * Kategori mengikuti kategori berita itu di WordPress, dibuat di lokal bila belum ada.
     * Portal tanpa kategori (atau "Uncategorized") jatuh ke nama cadangan.
     */
    private static function kategoriUntuk(array $p, string $cadangan): int
    {
        // Grup term dari _embed tidak dijamin urutannya, jadi dicari berdasarkan taxonomy.
        $nama = collect($p['_embedded']['wp:term'] ?? [])
            ->flatten(1)
            ->first(fn ($t) => ($t['taxonomy'] ?? null) === 'category' && ($t['name'] ?? '') !== 'Uncategorized')['name'] ?? $cadangan;

        // Tanpa cache statis: worker Octane hidup lama, id yang di-cache bisa menunjuk
        // kategori yang sudah dihapus. Maksimal 20 berita per sinkron, query-nya murah.
        return KategoriBerita::firstOrCreate(['slug' => Str::slug($nama)], ['nama' => $nama])->id;
    }

    private static function simpan(array $p, int $kategoriId, ?string $isiSampul): void
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
            'thumbnail' => self::simpanSampul($isiSampul),
        ]);
    }

    /** Alamat gambar unggulan, atau null bila tidak ada / bukan dari host portal. */
    private static function urlSampul(array $p): ?string
    {
        $media = $p['_embedded']['wp:featuredmedia'][0] ?? [];
        $url = $media['media_details']['sizes']['large']['source_url'] ?? $media['source_url'] ?? null;

        // Hanya dari host portal itu sendiri, supaya respons API tidak bisa menyuruh server mengunduh alamat lain.
        return $url && parse_url($url, PHP_URL_HOST) === parse_url(config('app.berita_wp.url'), PHP_URL_HOST)
            ? $url
            : null;
    }

    /** Semua sampul diunduh serentak; hasilnya map wp_id => isi berkas (null bila gagal). */
    private static function unduhSampul(Collection $pos): array
    {
        $url = $pos->mapWithKeys(fn (array $p) => [$p['id'] => self::urlSampul($p)])->filter();

        if ($url->isEmpty()) {
            return [];
        }

        $respons = Http::pool(fn (Pool $pool) => $url
            ->map(fn (string $u, int $id) => $pool->as((string) $id)->timeout(20)->get($u))
            ->all());

        return $url->keys()
            ->mapWithKeys(function (int $id) use ($respons): array {
                $r = $respons[(string) $id] ?? null;

                // Request yang gagal mengembalikan exception, bukan Response: berarti tanpa sampul.
                return [$id => $r instanceof Response && $r->successful() ? $r->body() : null];
            })
            ->all();
    }

    /** Sampul disalin ke disk sendiri sebagai WebP; gagal apa pun berarti tanpa sampul (gambar default). */
    private static function simpanSampul(?string $isi): ?string
    {
        $kompres = config('app.upload.kompres_gambar');
        $webp = $isi ? KompresGambar::keWebp($isi, $kompres['lebar_maks'], $kompres['kualitas']) : null;

        if ($webp === null) {
            return null;
        }

        $path = 'berita/' . NamaBerkas::acak('webp');
        Storage::disk('public')->put($path, $webp);

        return $path;
    }
}
