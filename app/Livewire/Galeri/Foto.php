<?php

namespace App\Livewire\Galeri;

use App\Livewire\HalamanSitus;
use App\Models\Galeri;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Foto extends Component
{
  use HalamanSitus;
  use WithPagination;

  public function render(): View
  {
    return $this->halaman('livewire.galeri.foto', 'Galeri Foto', [
      'daftar' => Galeri::latest('tanggal')->latest('id')->paginate(9),
    ], ['deskripsi' => 'Dokumentasi foto kegiatan Diskominfo Kota Kendari.']);
  }
}
