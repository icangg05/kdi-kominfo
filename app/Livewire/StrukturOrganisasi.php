<?php

namespace App\Livewire;

use App\Models\ProfilDinas;
use Livewire\Component;

class StrukturOrganisasi extends Component
{
  public function render()
  {
    $data = ProfilDinas::where('jenis', 'struktur-organisasi')->value('konten');
    return view('livewire.struktur-organisasi', compact(
      'data',
    ));
  }
}
