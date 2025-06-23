<?php

namespace App\Livewire;

use App\Models\ProfilDinas;
use Livewire\Component;

class Tupoksi extends Component
{
  public function render()
  {
    $tugas  = ProfilDinas::where('jenis', 'tugas')->value('konten');
    $fungsi = ProfilDinas::where('jenis', 'fungsi')->value('konten');
    // dd($fungsi);

    return view('livewire.tupoksi', compact(
      'tugas', 'fungsi'
    ));
  }
}
