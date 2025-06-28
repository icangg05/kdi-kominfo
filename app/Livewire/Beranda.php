<?php

namespace App\Livewire;

use App\Models\Galeri;
use App\Models\ProfilDinas;
use App\Models\ProfilPimpinan;
use Livewire\Component;

class Beranda extends Component
{
  public function render()
  {
    $sambutanKadis   = ProfilDinas::where('jenis', 'sambutan-kadis')->value('konten');
    $taglineSambutan = ProfilDinas::where('jenis', 'tagline-sambutan')->value('konten');
    $fotoKadis       = ProfilPimpinan::first()?->foto[0];
    $galeri          = Galeri::limit(5)->get();
    $berita          = Galeri::limit(6)->get();

    return view('livewire.beranda', compact(
      'sambutanKadis',
      'taglineSambutan',
      'fotoKadis',
      'galeri',
      'berita',
    ));
  }
}
