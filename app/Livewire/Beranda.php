<?php

namespace App\Livewire;

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

    return view('livewire.beranda', compact(
      'sambutanKadis',
      'taglineSambutan',
      'fotoKadis',
    ));
  }
}
