<?php

namespace App\Livewire\Profil;

use App\Livewire\HalamanSitus;
use App\Models\Pegawai as ModelPegawai;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Pegawai extends Component
{
  use HalamanSitus;
  use WithPagination;

  #[Url(except: '')]
  public string $search = '';

  public function updatedSearch(): void
  {
    $this->resetPage();
  }

  public function render(): View
  {
    return $this->halaman('livewire.profil.pegawai', 'Profil Pegawai', [
      'daftar' => ModelPegawai::query()
        ->with('jabatan:id,nama')
        ->when(trim($this->search), fn ($q, $cari) => $q->where('nama', 'like', "%{$cari}%"))
        // Sama dengan tabel admin: terbaru dulu, id sebagai penentu urutan.
        ->latest()->latest('id')
        ->paginate(9),
    ], ['deskripsi' => 'Daftar pegawai Diskominfo Kota Kendari.']);
  }
}
