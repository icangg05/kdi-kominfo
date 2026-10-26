<?php

namespace App\Livewire\Profil;

use App\Livewire\HalamanSitus;
use App\Models\ProfilPimpinan;
use App\Support\Situs;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Pimpinan extends Component
{
  use HalamanSitus;

  public function render(): View
  {
    $data = ProfilPimpinan::with('pegawai.jabatan')->first();
    $nama = $data?->pegawai?->nama;

    return $this->halaman('livewire.profil.pimpinan', 'Profil Pimpinan', [
      'nama' => $nama,
      'jabatan' => $data?->pegawai?->jabatan?->nama,
      'konten' => $data?->konten,
      'foto' => Situs::url($data?->pegawai?->foto),
      'fotoTambahan' => collect($data?->foto)->map(Situs::url(...))->filter()->values()->all(),
    ], ['deskripsi' => 'Profil ' . ($nama ?? 'Kepala Dinas') . ' Kominfo Kota Kendari.']);
  }
}
