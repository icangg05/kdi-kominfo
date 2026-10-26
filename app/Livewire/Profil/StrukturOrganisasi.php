<?php

namespace App\Livewire\Profil;

use App\Livewire\HalamanSitus;
use App\Models\Pegawai;
use App\Models\ProfilDinas;
use App\Support\Situs;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class StrukturOrganisasi extends Component
{
  use HalamanSitus;

  public function render(): View
  {
    $bagan = $this->pejabat(ProfilDinas::konten('bagan-organisasi'), Pegawai::pluck('nama', 'id')->all());
    $bagan = is_array($bagan) ? $bagan : [];
    $gambar = Situs::url(ProfilDinas::konten('struktur-organisasi'));

    return $this->halaman('livewire.profil.struktur-organisasi', 'Struktur Organisasi', [
      'bagan' => $bagan,
      'adaBagan' => filled($bagan['nama'] ?? null) || filled($bagan['pejabat'] ?? null) || ! empty($bagan['anak']),
      'gambar' => $gambar,
      // Bagan resmi bisa gambar atau PDF; PDF cukup ditautkan.
      'pdf' => str_ends_with(strtolower((string) $gambar), '.pdf'),
    ], ['deskripsi' => 'Bagan struktur organisasi Diskominfo Kota Kendari.']);
  }

  /** Tiap kotak bagan ({nama, pegawai_id}) diberi nama pejabatnya; pegawai yang sudah dihapus jadi null. */
  private function pejabat(mixed $simpul, array $namaPegawai): mixed
  {
    if (! is_array($simpul)) {
      return $simpul;
    }

    $simpul = array_map(fn ($anak) => $this->pejabat($anak, $namaPegawai), $simpul);

    if (array_key_exists('nama', $simpul)) {
      $simpul['pejabat'] = $namaPegawai[$simpul['pegawai_id'] ?? ''] ?? null;
      unset($simpul['pegawai_id']);
    }

    return $simpul;
  }
}
