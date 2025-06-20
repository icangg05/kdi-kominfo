<?php

use Illuminate\Support\Facades\Route;


Route::get('/', App\Livewire\Beranda::class)->name('beranda');

// Route Profil Dinas
Route::get('/profil-dinas/tentang-kami', App\Livewire\TentangKami::class)->name('profil-dinas.tentang-kami');
Route::get('/profil-dinas/profil-pimpinan', App\Livewire\ProfilPimpinan::class)->name('profil-dinas.profil-pimpinan');
Route::get('/profil-dinas/profil-pegawai', App\Livewire\ProfilPegawai::class)->name('profil-dinas.profil-pegawai');
Route::get('/profil-dinas/struktur-organisasi', App\Livewire\StrukturOrganisasi::class)->name('profil-dinas.struktur-organisasi');

Route::get('/berita', App\Livewire\Berita::class)->name('berita.index');
Route::get('/berita/{slug}', App\Livewire\BeritaShow::class)->name('berita.show');

