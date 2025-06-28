<?php

namespace App\Livewire;

use App\Models\Pegawai;
use Livewire\Component;
use Livewire\WithPagination;

class ProfilPegawai extends Component
{
  use WithPagination;

  public $perPage = 9;
  public $search  = '';

  public function updatedSearch($value)
  {
    $this->search = $value;
  }

  public function loadMore()
  {
    $this->perPage += 9;
  }

  public function render()
  {
    $data = Pegawai::with('jabatan')->where('nama', 'like', '%' . $this->search . '%')
      ->paginate($this->perPage);

    return view('livewire.profil-pegawai', compact(
      'data',
    ));
  }
}
