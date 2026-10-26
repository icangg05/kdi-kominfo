<?php

namespace App\Livewire\Layanan;

use App\Livewire\HalamanSitus;
use App\Models\PengajuanLayanan;
use App\Models\ProfilDinas;
use App\Support\Konten;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Rincian extends Component
{
  use HalamanSitus;

  public string $nama;

  public function mount(string $nama): void
  {
    abort_unless(isset(Konten::LAYANAN[$nama]), 404);
    $this->nama = $nama;
  }

  public function render(): View
  {
    $isi = Konten::LAYANAN[$this->nama];

    return $this->halaman('livewire.layanan.rincian', $isi['judul'], [
      'isi' => $isi,
      // Kontak diambil dari pengaturan admin supaya tidak perlu diubah di dua tempat.
      'pengaturan' => ProfilDinas::pengaturan(),
      'bisaDiajukan' => isset(PengajuanLayanan::JENIS[$this->nama]),
    ], ['deskripsi' => $isi['ringkas']]);
  }
}
