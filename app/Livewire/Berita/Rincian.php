<?php

namespace App\Livewire\Berita;

use App\Livewire\HalamanSitus;
use App\Models\Berita;
use App\Support\Situs;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Rincian extends Component
{
  use HalamanSitus;

  public Berita $berita;

  public function mount(string $slug): void
  {
    $this->berita = Berita::with('kategori:id,nama,slug')->where('slug', $slug)->firstOrFail();
    // Hanya saat halaman dibuka, bukan setiap permintaan Livewire berikutnya.
    $this->berita->increment('total_lihat');
  }

  public function render(): View
  {
    $berita = $this->berita;
    $sampul = Situs::url($berita->thumbnail);

    return $this->halaman('livewire.berita.rincian', $berita->judul, [
      'sampul' => $sampul,
      'tautan' => Situs::asal() . "/berita/{$berita->slug}",
      'lainnya' => Berita::with('kategori:id,nama,slug')
        ->whereKeyNot($berita->id)
        ->latest('tanggal')->latest('id')
        ->limit(4)
        ->get(),
    ], [
      'deskripsi' => $berita->ringkasan(),
      'gambar' => $sampul,
      'jenis' => 'article',
      'terbit' => $berita->tanggal?->toDateString(),
    ]);
  }
}
