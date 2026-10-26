<?php

use App\Http\Middleware\VisitorMiddleware;
use App\Livewire;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Support\Konten;
use App\Support\Situs;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
 * Situs publik: tiap halaman adalah komponen Livewire penuh (app/Livewire) di dalam
 * layout components.layouts.situs. Panel admin Filament ada di /admin.
 */

Route::middleware(VisitorMiddleware::class)->group(function () {
  Route::get('/', Livewire\Beranda::class)->name('beranda');

  Route::get('/berita', Livewire\Berita\Daftar::class)->name('berita');
  Route::get('/berita/{slug}', Livewire\Berita\Rincian::class)->name('berita.rincian');

  Route::get('/dokumen', Livewire\Dokumen\Daftar::class)->name('dokumen');

  Route::get('/galeri', Livewire\Galeri\Foto::class)->name('galeri');
  Route::get('/galeri/video', Livewire\Galeri\Video::class)->name('galeri.video');

  Route::prefix('profil-dinas')->name('profil.')->group(function () {
    Route::get('/tentang-kami', Livewire\Profil\TentangKami::class)->name('tentang-kami');
    Route::get('/tupoksi', Livewire\Profil\Tupoksi::class)->name('tupoksi');
    Route::get('/profil-pimpinan', Livewire\Profil\Pimpinan::class)->name('pimpinan');
    Route::get('/profil-pegawai', Livewire\Profil\Pegawai::class)->name('pegawai');
    Route::get('/struktur-organisasi', Livewire\Profil\StrukturOrganisasi::class)->name('struktur-organisasi');
  });

  // Didaftarkan sebelum /layanan/{nama} supaya "cek-pengajuan" tidak dianggap nama layanan.
  Route::get('/layanan/cek-pengajuan', Livewire\Layanan\CekPengajuan::class)->name('layanan.cek');
  Route::get('/layanan/{nama}', Livewire\Layanan\Rincian::class)->name('layanan');
});

// Alamat memakai slug judul supaya id dokumen tidak terlihat.
Route::get('/download/{dokumen:slug}', function (Dokumen $dokumen) {
  // Berkas dicek lebih dulu: kalau hilang, jangan hitung sebagai unduhan
  // dan balas 404, bukan 500.
  abort_unless(Storage::disk('public')->exists($dokumen->file), 404);

  $dokumen->increment('total_unduhan');

  $nama = $dokumen->slug . '.' . pathinfo($dokumen->file, PATHINFO_EXTENSION);

  return Storage::disk('public')->download($dokumen->file, $nama);
})->name('download');

Route::get('/sitemap.xml', function () {
  $asal = Situs::asal();
  $statis = collect(Konten::MENU_UTAMA)
    ->flatMap(fn ($m) => [$m['href'], ...array_column($m['anak'] ?? [], 'href')])
    // Halaman cek status bersifat pribadi (noindex), jadi tidak ikut sitemap.
    ->reject(fn ($href) => $href === '/layanan/cek-pengajuan')
    ->unique();

  $entri = $statis->map(fn ($p) => '<url><loc>' . e($asal . $p) . '</loc></url>')
    ->concat(Berita::latest('tanggal')->get(['slug', 'updated_at'])->map(fn (Berita $b) => '<url><loc>' . e($asal . '/berita/' . rawurlencode($b->slug)) . '</loc>'
      . ($b->updated_at ? '<lastmod>' . $b->updated_at->toAtomString() . '</lastmod>' : '') . '</url>'));

  return response("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">{$entri->implode('')}</urlset>")
    ->header('Content-Type', 'application/xml; charset=utf-8');
})->name('sitemap');

Route::get('/robots.txt', fn () => response("User-agent: *\nDisallow: /admin\nDisallow: /layanan/cek-pengajuan\n\nSitemap: " . Situs::asal() . "/sitemap.xml\n")
  ->header('Content-Type', 'text/plain; charset=utf-8'))->name('robots');
