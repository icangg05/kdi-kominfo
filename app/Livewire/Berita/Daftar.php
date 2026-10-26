<?php

namespace App\Livewire\Berita;

use App\Livewire\HalamanSitus;
use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Daftar extends Component
{
  use HalamanSitus;
  use WithPagination;

  // Nama parameter sama dengan versi lama (?search=&kategori=&page=), jadi tautan lama dan form header tetap jalan.
  #[Url(except: '')]
  public string $search = '';

  #[Url(except: '')]
  public string $kategori = '';

  public function updatedSearch(): void
  {
    $this->resetPage();
  }

  public function pilihKategori(string $slug): void
  {
    $this->kategori = $slug;
    $this->resetPage();
  }

  public function render(): View
  {
    $daftar = Berita::query()
      ->with('kategori:id,nama,slug')
      ->when($this->kategori, fn ($q, $slug) => $q->whereRelation('kategori', 'slug', $slug))
      ->when(trim($this->search), fn ($q, $cari) => $q->where('judul', 'like', "%{$cari}%"))
      // id sebagai pemecah seri: tanpa itu berita bertanggal sama bisa muncul lagi di halaman berikutnya.
      ->latest('tanggal')->latest('id')
      ->paginate(6);

    return $this->halaman('livewire.berita.daftar', 'Berita', [
      'daftar' => $daftar,
      'kategoriBerita' => KategoriBerita::has('berita')->orderBy('nama')->get(['nama', 'slug']),
    ], ['deskripsi' => 'Kabar dan siaran pers Diskominfo Kota Kendari.']);
  }
}
