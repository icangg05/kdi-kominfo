<?php

namespace App\Livewire\Galeri;

use App\Livewire\HalamanSitus;
use App\Models\Video as ModelVideo;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Video extends Component
{
  use HalamanSitus;
  use WithPagination;

  public function render(): View
  {
    return $this->halaman('livewire.galeri.video', 'Galeri Video', [
      'daftar' => ModelVideo::latest('tanggal')->latest('id')->paginate(9),
    ], ['deskripsi' => 'Dokumentasi video kegiatan Diskominfo Kota Kendari.']);
  }
}
