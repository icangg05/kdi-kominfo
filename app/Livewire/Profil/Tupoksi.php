<?php

namespace App\Livewire\Profil;

use App\Livewire\HalamanSitus;
use App\Models\ProfilDinas;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Tupoksi extends Component
{
  use HalamanSitus;

  public function render(): View
  {
    return $this->halaman('livewire.profil.tupoksi', 'Tugas dan Fungsi', [
      'tugas' => ProfilDinas::konten('tugas'),
      'fungsi' => ProfilDinas::nilai('fungsi'),
    ], ['deskripsi' => 'Tugas pokok dan fungsi Diskominfo Kota Kendari.']);
  }
}
