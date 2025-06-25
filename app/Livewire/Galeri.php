<?php

namespace App\Livewire;

use App\Models\Galeri as ModelsGaleri;
use Livewire\Component;

class Galeri extends Component
{
  public function render()
  {
    $data = ModelsGaleri::latest('tanggal')->paginate(9);

    return view('livewire.galeri', compact('data'));
  }
}
