<?php

namespace App\Http\Controllers\Api;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\KategoriBerita;
use App\Models\KategoriDokumen;
use App\Models\Pegawai;
use App\Models\ProfilDinas;
use App\Models\ProfilPimpinan;
use App\Models\Video;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Satu-satunya sumber data untuk frontend Astro.
 * Semua path berkas dikembalikan relatif (/storage/...) supaya origin-agnostic.
 */
class SiteController
{
  public function pengaturan(): array
  {
    $atur = ProfilDinas::pengaturan();

    return [
      'pengaturan' => $atur,
      'survei' => ($atur['survei_aktif'] ?? false) ? ($atur['survei_url'] ?? null) : null,
      // Astro meneruskan cookie pengunjung ke sini, jadi sesi admin Filament ikut terbaca.
      'admin' => auth()->check(),
      'kategoriBerita' => KategoriBerita::orderBy('nama')->get(['nama', 'slug']),
      'kategoriDokumen' => KategoriDokumen::orderBy('nama')->get(['nama', 'slug']),
    ];
  }

  public function beranda(): array
  {
    return [
      'sambutanKadis' => ProfilDinas::konten('sambutan-kadis'),
      'taglineSambutan' => ProfilDinas::nilai('tagline-sambutan'),
      'kadis' => ($kadis = ProfilPimpinan::with('pegawai.jabatan')->first())?->pegawai ? [
        'nama' => $kadis->pegawai->nama,
        'jabatan' => $kadis->pegawai->jabatan?->nama,
        'foto' => $this->url($kadis->pegawai->foto),
      ] : null,
      'galeri' => Galeri::latest('tanggal')->latest('id')->limit(5)->get()->map($this->galeriItem(...)),
      'berita' => Berita::with('kategori:id,nama,slug')->latest('tanggal')->latest('id')->limit(6)->get()->map($this->beritaItem(...)),
      'pengunjung' => [
        'hariIni' => Visitor::whereDate('date', today())->count(),
        'total' => Visitor::count(),
      ],
    ];
  }

  public function berita(Request $request): array
  {
    $data = Berita::query()
      ->with('kategori:id,nama,slug')
      ->when($request->string('kategori')->toString(), fn ($q, $slug) => $q->whereRelation('kategori', 'slug', $slug))
      ->when($request->string('search')->toString(), fn ($q, $cari) => $q->where('judul', 'like', "%{$cari}%"))
      ->latest('tanggal')->latest('id')
      ->paginate(perPage: 6, page: $request->integer('page', 1));

    return $this->paginated($data, $this->beritaItem(...));
  }

  public function beritaShow(string $slug): array
  {
    $data = Berita::with('kategori:id,nama,slug')->where('slug', $slug)->firstOrFail();
    $data->increment('total_lihat');

    return [
      ...$this->beritaItem($data),
      'konten' => $data->konten,
      'sumber' => $data->wp_url,
      'lainnya' => Berita::with('kategori:id,nama,slug')
        ->whereKeyNot($data->id)
        ->latest('tanggal')->latest('id')
        ->limit(4)
        ->get()
        ->map($this->beritaItem(...)),
    ];
  }

  /** Bahan sitemap.xml: cukup slug dan waktu ubah terakhir tiap berita. */
  public function sitemap(): array
  {
    return [
      'berita' => Berita::latest('tanggal')->get(['slug', 'updated_at'])
        ->map(fn (Berita $b) => ['slug' => $b->slug, 'diubah' => $b->updated_at?->toAtomString()]),
    ];
  }

  public function galeri(Request $request): array
  {
    $data = Galeri::latest('tanggal')->latest('id')->paginate(perPage: 9, page: $request->integer('page', 1));

    return $this->paginated($data, $this->galeriItem(...));
  }

  public function video(Request $request): array
  {
    $data = Video::latest('tanggal')->latest('id')->paginate(perPage: 9, page: $request->integer('page', 1));

    return $this->paginated($data, fn (Video $v) => [
      'judul' => $v->judul,
      'tanggal' => $v->tanggal?->toDateString(),
      'tautan' => $v->tautan,
      'youtubeId' => $v->youtubeId(),
    ]);
  }

  public function dokumen(Request $request): array
  {
    $data = Dokumen::query()
      ->with('kategori:id,nama,slug')
      ->when($request->string('kategori')->toString(), fn ($q, $slug) => $q->whereRelation('kategori', 'slug', $slug))
      ->when($request->string('search')->toString(), fn ($q, $cari) => $q->where('judul', 'like', "%{$cari}%"))
      ->latest()
      ->latest('id')
      ->paginate(perPage: 6, page: $request->integer('page', 1));

    return $this->paginated($data, function (Dokumen $d) {
      $ada = $d->file && Storage::disk('public')->exists($d->file);

      return [
        'id' => $d->id,
        'judul' => $d->judul,
        'deskripsi' => $d->deskripsi,
        'kategori' => $d->kategori?->only(['nama', 'slug']),
        'totalUnduhan' => $d->total_unduhan,
        'ekstensi' => pathinfo((string) $d->file, PATHINFO_EXTENSION),
        'tanggal' => $d->created_at?->toIso8601String(),
        // Byte dan alamat unduhan; null bila berkas tidak tercatat atau hilang dari penyimpanan.
        'ukuran' => $ada ? Storage::disk('public')->size($d->file) : null,
        'unduh' => $ada ? "/download/{$d->slug}" : null,
      ];
    });
  }

  public function pegawai(Request $request): array
  {
    $data = Pegawai::query()
      ->with('jabatan:id,nama')
      ->when($request->string('search')->toString(), fn ($q, $cari) => $q->where('nama', 'like', "%{$cari}%"))
      // Sama dengan tabel admin: terbaru dulu, id sebagai penentu urutan.
      ->latest()->latest('id')
      ->paginate(perPage: 9, page: $request->integer('page', 1));

    return $this->paginated($data, fn (Pegawai $p) => [
      'nama' => $p->nama,
      'nip' => $p->nip,
      'jabatan' => $p->jabatan?->nama,
      'foto' => $this->url($p->foto),
    ]);
  }

  public function profilPimpinan(): array
  {
    $data = ProfilPimpinan::with('pegawai.jabatan')->first();

    return [
      'nama' => $data?->pegawai?->nama,
      'jabatan' => $data?->pegawai?->jabatan?->nama,
      'konten' => $data?->konten,
      'foto' => $this->url($data?->pegawai?->foto),
      'fotoTambahan' => collect($data?->foto)->map($this->url(...))->filter()->values(),
    ];
  }

  /** Halaman statis yang isinya dikelola lewat admin (tentang-kami, tupoksi, struktur-organisasi). */
  public function profilDinas(string $halaman): array
  {
    return match ($halaman) {
      'tentang-kami' => [
        'sejarah' => ProfilDinas::konten('sejarah'),
        'visi' => ProfilDinas::konten('visi'),
        'misi' => ProfilDinas::nilai('misi'),
        'fotoDiskominfo' => collect(ProfilDinas::nilai('foto-diskominfo'))->map($this->url(...))->filter()->values(),
      ],
      'tupoksi' => [
        'tugas' => ProfilDinas::konten('tugas'),
        'fungsi' => ProfilDinas::nilai('fungsi'),
      ],
      'struktur-organisasi' => [
        'bagan' => $this->pejabat(ProfilDinas::konten('bagan-organisasi'), Pegawai::pluck('nama', 'id')->all()),
        'gambar' => $this->url(ProfilDinas::konten('struktur-organisasi')),
      ],
      default => abort(404),
    };
  }

  /** Tiap kotak bagan ({nama, pegawai_id}) dikirim dengan nama pejabatnya; pegawai yang sudah dihapus jadi null. */
  private function pejabat(mixed $simpul, array $namaPegawai): mixed
  {
    if (! is_array($simpul)) {
      return $simpul;
    }

    $simpul = array_map(fn ($anak) => $this->pejabat($anak, $namaPegawai), $simpul);

    if (array_key_exists('nama', $simpul)) {
      $simpul['pejabat'] = $namaPegawai[$simpul['pegawai_id'] ?? ''] ?? null;
      unset($simpul['pegawai_id']);
    }

    return $simpul;
  }

  private function beritaItem(Berita $b): array
  {
    return [
      'judul' => $b->judul,
      'slug' => $b->slug,
      'tanggal' => $b->tanggal?->toDateString(),
      'kategori' => $b->kategori?->only(['nama', 'slug']),
      'totalLihat' => $b->total_lihat,
      'thumbnail' => $this->url($b->thumbnail),
      // Entitas didekode di sini: Astro meng-escape ulang, jadi &nbsp; akan tampil mentah di meta description.
      'ringkasan' => str(html_entity_decode(strip_tags((string) $b->konten)))->squish()->limit(160)->value(),
    ];
  }

  private function galeriItem(Galeri $g): array
  {
    return [
      'judul' => $g->judul,
      'tanggal' => $g->tanggal?->toDateString(),
      'gambar' => $this->url($g->gambar),
    ];
  }

  /**
   * Path relatif (/storage/...), bukan URL absolut: Caddy meneruskan /storage ke
   * Laravel, jadi berkas tetap ketemu dari domain mana pun situs disajikan.
   * Null bila berkasnya tidak ada di disk, supaya situs memakai gambar default.
   */
  private function url(?string $path): ?string
  {
    $path = ltrim((string) $path, '/');

    return $path !== '' && Storage::disk('public')->exists($path) ? "/storage/{$path}" : null;
  }

  private function paginated(\Illuminate\Contracts\Pagination\LengthAwarePaginator $page, callable $map): array
  {
    return [
      'data' => collect($page->items())->map($map)->values(),
      'meta' => [
        'page' => $page->currentPage(),
        'lastPage' => $page->lastPage(),
        'total' => $page->total(),
      ],
    ];
  }
}
