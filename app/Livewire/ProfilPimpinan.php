<?php

namespace App\Livewire;

use App\Models\ProfilPimpinan as ModelsProfilPimpinan;
use Livewire\Component;

class ProfilPimpinan extends Component
{
  public function render()
  {
    $data = ModelsProfilPimpinan::first();

    return view('livewire.profil-pimpinan', compact(
      'data',
    ));
  }
}
