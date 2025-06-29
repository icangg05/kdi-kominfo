<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Dokumen extends Component
{
  use WithPagination;

  #[Url()]
  public $kategori = '';


  public function changeKategori($value)
  {
    $this->resetPage();
    $this->kategori = $value;
  }

  public function render()
  {
    $data = DB::table('dokumen')
      ->select('dokumen.*', 'kategori_dokumen.nama as kategori_nama', 'kategori_dokumen.slug as kategori_slug')
      ->leftJoin('kategori_dokumen', 'dokumen.kategori_dokumen_id', '=', 'kategori_dokumen.id')
      ->orderBy('dokumen.created_at', 'desc');

    if ($this->kategori)
      $data = $data->where('kategori_dokumen.slug', '=', $this->kategori);

    if (request('search'))
      $data = $data->where('dokumen.judul', 'like', '%' . request('search') . '%');

    $data = $data->paginate(6);
    
    return view('livewire.dokumen', compact(
      'data',
    ));
  }
}
