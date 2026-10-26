<?php

namespace App\Livewire\Profil;

use App\Livewire\HalamanSitus;
use App\Models\ProfilDinas;
use App\Support\Situs;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TentangKami extends Component
{
  use HalamanSitus;

  public function render(): View
  {
    return $this->halaman('livewire.profil.tentang-kami', 'Tentang Kami', [
      'sejarah' => ProfilDinas::konten('sejarah'),
      'visi' => ProfilDinas::konten('visi'),
      'misi' => ProfilDinas::nilai('misi'),
      // Admin membatasi foto kantor maksimal 3; susunannya menyesuaikan jumlah.
      'foto' => collect(ProfilDinas::nilai('foto-diskominfo'))->map(Situs::url(...))->filter()->take(3)->values()->all(),
    ], ['deskripsi' => 'Sejarah, visi, dan misi Diskominfo Kota Kendari.']);
  }
}
