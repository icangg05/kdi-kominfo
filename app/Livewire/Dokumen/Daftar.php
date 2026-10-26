<?php

namespace App\Livewire\Dokumen;

use App\Livewire\HalamanSitus;
use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Daftar extends Component
{
  use HalamanSitus;
  use WithPagination;

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
    $daftar = Dokumen::query()
      ->with('kategori:id,nama,slug')
      ->when($this->kategori, fn ($q, $slug) => $q->whereRelation('kategori', 'slug', $slug))
      ->when(trim($this->search), fn ($q, $cari) => $q->where('judul', 'like', "%{$cari}%"))
      ->latest()->latest('id')
      ->paginate(6);

    return $this->halaman('livewire.dokumen.daftar', 'Dokumen', [
      'daftar' => $daftar,
      'kategoriDokumen' => KategoriDokumen::has('dokumen')->orderBy('nama')->get(['nama', 'slug']),
    ], ['deskripsi' => 'Unduh dokumen resmi dan produk hukum Diskominfo Kota Kendari.']);
  }
}
