<?php

namespace App\Livewire;

use Livewire\Component;

class Layanan extends Component
{
  public $nama_layanan;

  public function mount($nama_layanan)
  {
    $this->nama_layanan = $nama_layanan;
  }

  public function render()
  {
    if ($this->nama_layanan == 'subdomain-hosting')
      return view('livewire.layanan-subdomain-hosting');
    elseif ($this->nama_layanan == 'email-dinas')
      return view('livewire.layanan-email-dinas');
    elseif ($this->nama_layanan == 'pengajuan-tte')
      return view('livewire.layanan-pengajuan-tte');
    elseif ($this->nama_layanan == 'telpon-darurat-112')
      return view('livewire.layanan-telpon-darurat-112');
    else
      return abort(404);
  }
}
