<?php

use Illuminate\Support\Facades\Route;


Route::get('/', App\Livewire\Beranda::class)->name('beranda');
Route::get('/profil-dinas/pimpinan', App\Livewire\ProfilPimpinan::class)->name('profil.profil-pimpinan');
