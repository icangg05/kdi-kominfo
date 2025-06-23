<?php

namespace App\Livewire;

use App\Models\ProfilDinas;
use App\Models\ProfilPimpinan;
use Livewire\Component;

class TentangKami extends Component
{
  public function render()
  {
    $sejarah        = ProfilDinas::where('jenis', 'sejarah')->value('konten');
    $visi           = ProfilDinas::where('jenis', 'visi')->value('konten');
    $misi           = ProfilDinas::where('jenis', 'misi')->value('konten');
    $fotoDiskominfo = ProfilDinas::where('jenis', 'foto-diskominfo')->value('konten');
    $awalPeriode    = ProfilPimpinan::first()?->awal_periode;
    // dd($fotoDiskominfo);

    return view('livewire.tentang-kami', compact(
      'sejarah',
      'awalPeriode',
      'visi',
      'misi',
      'fotoDiskominfo'
    ));
  }
}
