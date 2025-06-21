<?php

use Illuminate\Support\Facades\Route;


// Page Beranda
Route::get('/', App\Livewire\Beranda::class)->name('beranda');

// Page Profil Dinas
Route::get('/profil-dinas/tentang-kami', App\Livewire\TentangKami::class)->name('profil-dinas.tentang-kami');
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
