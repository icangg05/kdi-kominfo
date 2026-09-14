<?php

use App\Http\Controllers\Api\SiteController;
use App\Models\Dokumen;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
 * Laravel hanya melayani API untuk frontend Astro, panel admin Filament (/admin),
 * dan berkas di /storage. Sisanya di-reverse-proxy Caddy ke Astro.
 */

Route::prefix('api')->name('api.')->group(function () {
  Route::get('/pengaturan', [SiteController::class, 'pengaturan']);
  Route::get('/beranda', [SiteController::class, 'beranda']);
  Route::get('/berita', [SiteController::class, 'berita']);
  Route::get('/berita/{slug}', [SiteController::class, 'beritaShow']);
  Route::get('/sitemap', [SiteController::class, 'sitemap']);
  Route::get('/galeri', [SiteController::class, 'galeri']);
  Route::get('/video', [SiteController::class, 'video']);
  Route::get('/dokumen', [SiteController::class, 'dokumen']);
  Route::get('/pegawai', [SiteController::class, 'pegawai']);
  Route::get('/profil-pimpinan', [SiteController::class, 'profilPimpinan']);
  Route::get('/profil-dinas/{halaman}', [SiteController::class, 'profilDinas']);
});

Route::get('/download/{dokumen}', function (Dokumen $dokumen) {
  // Berkas dicek lebih dulu: kalau hilang, jangan hitung sebagai unduhan
  // dan balas 404, bukan 500.
  abort_unless(Storage::disk('public')->exists($dokumen->file), 404);

  $dokumen->increment('total_unduhan');

  $nama = str($dokumen->judul)->slug() . '.' . pathinfo($dokumen->file, PATHINFO_EXTENSION);

  return Storage::disk('public')->download($dokumen->file, $nama);
})->name('download');
