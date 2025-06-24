<?php

namespace App\Livewire;

use App\Models\Berita;
use Livewire\Component;

class BeritaShow extends Component
{
  public $slug;

  public function mount($slug)
  {
    $this->slug = $slug;
  }

  public function render()
  {
    $data = Berita::with('kategori')->where('slug', $this->slug)->first();
    $data->increment('total_lihat');

    return view('livewire.berita-show', compact(
      'data',
    ));
  }
}
