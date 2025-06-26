<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfilDinasController;
use Illuminate\Support\Facades\Route;


// Page Login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');


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



// Page Dashboard
Route::middleware('auth')->group(function() {
  // Logout
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
  Route::get('/dashboard/profil-dinas/{jenis}', [ProfilDinasController::class, 'index'])->name('admin.profil-dinas');
  Route::post('/dashboard/profil-dinas/{jenis}', [ProfilDinasController::class, 'save'])->name('admin.profil-dinas.save');
  Route::delete('/dashboard/profil-dinas/{jenis}', [ProfilDinasController::class, 'delete'])->name('admin.profil-dinas.delete');
  
});