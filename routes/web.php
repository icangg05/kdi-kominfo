<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumenController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\KategoriBeritaController;
use App\Http\Controllers\Admin\KategoriDokumenController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\ProfilDinasController;
use App\Http\Controllers\ProfilPimpinanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Page Login
Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest')->name('authenticate');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// Page Beranda
Route::get('/', App\Livewire\Beranda::class)->name('beranda');

// Page Profil Dinas
Route::get('/profil-dinas/tentang-kami', App\Livewire\TentangKami::class)->name('profil-dinas.tentang-kami');
Route::get('/profil-dinas/tupoksi', App\Livewire\Tupoksi::class)->name('profil-dinas.tupoksi');
Route::get('/profil-dinas/profil-pimpinan', App\Livewire\ProfilPimpinan::class)->name('profil-dinas.profil-pimpinan');
Route::get('/profil-dinas/profil-pegawai', App\Livewire\ProfilPegawai::class)->name('profil-dinas.profil-pegawai');
Route::get('/profil-dinas/struktur-organisasi', App\Livewire\StrukturOrganisasi::class)->name('profil-dinas.struktur-organisasi');

// Page Berita
Route::get('/berita', App\Livewire\Berita::class)->name('berita.index');
Route::get('/berita/{slug}', App\Livewire\BeritaShow::class)->name('berita.show');
// Page Galeri
Route::get('/galeri', App\Livewire\Galeri::class)->name('galeri.index');
// Page Dokumen
Route::get('/dokumen', App\Livewire\Dokumen::class)->name('dokumen.index');
// Page Layanan
Route::get('/layanan/{nama_layanan}', App\Livewire\Layanan::class)->name('layanan');
// Download file
Route::get('/download/{id}', function ($id) {
  $data = App\Models\Dokumen::findOrFail($id);
  $path = $data->file;
  $extension = pathinfo($path, PATHINFO_EXTENSION);
  $filename = str()->slug($data->judul) . '.' . $extension;

  $data->increment('total_unduhan');

  return Storage::disk('public')->download($path, $filename);
})->name('download');

// Page Dashboard
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
  Route::get('/', [DashboardController::class, 'index'])->name('index');

  Route::get('/profil-pimpinan', [ProfilPimpinanController::class, 'index'])->name('profil-pimpinan');
  Route::post('/profil-pimpinan', [ProfilPimpinanController::class, 'save'])->name('profil-pimpinan.save');

  Route::get('/profil-dinas/{jenis}', [ProfilDinasController::class, 'index'])->name('profil-dinas');
  Route::post('/profil-dinas/{jenis}', [ProfilDinasController::class, 'save'])->name('profil-dinas.save');
  Route::delete('/profil-dinas/{jenis}', [ProfilDinasController::class, 'delete'])->name('profil-dinas.delete');

  Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai');
  Route::post('/pegawai', [PegawaiController::class, 'save'])->name('pegawai.save');
  Route::delete('/pegawai', [PegawaiController::class, 'delete'])->name('pegawai.delete');
  Route::get('/pegawai/refresh', [PegawaiController::class, 'refreshTable'])->name('pegawai.refresh');

  Route::resource('/kategori-berita', KategoriBeritaController::class)->except(['show', 'edit']);
  Route::resource('/kategori-dokumen', KategoriDokumenController::class)->except(['show', 'edit']);

  // Route Berita
  Route::resource('/berita', BeritaController::class)->except(['show', 'edit']);

  // Route Berita
  Route::resource('/dokumen', DokumenController::class)->except(['show', 'edit']);

  // Route Galeri
  Route::resource('/galeri', GaleriController::class)->except(['show', 'edit']);
});
