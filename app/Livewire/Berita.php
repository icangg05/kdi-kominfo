<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Berita extends Component
{
  use WithPagination; 

  #[Url()]
  public $search   = '';
  #[Url()]
  public $kategori = '';


  public function updatedSearch($value)
  {
    $this->resetPage();
    $this->search = $value;
  }

  public function changeKategori($value)
  {
    $this->resetPage();
    $this->kategori = $value;
  }


  public function render()
  {
    $data = DB::table('berita')
      ->select('berita.*', 'kategori_berita.nama as kategori_nama', 'kategori_berita.slug as kategori_slug')
      ->leftJoin('kategori_berita', 'berita.kategori_berita_id', '=', 'kategori_berita.id')
      ->orderBy('berita.created_at', 'desc');

    if ($this->kategori)
      $data = $data->where('kategori_berita.slug', '=', $this->kategori);

    if ($this->search)
      $data = $data->where('berita.judul', 'like', '%' . $this->search . '%');

    $data = $data->paginate(6);

    return view('livewire.berita', compact(
      'data',
    ));
  }
}
