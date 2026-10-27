<?php

namespace App\Livewire;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\KategoriBerita;
use App\Models\KategoriDokumen;
use App\Models\ProfilDinas;
use App\Models\ProfilPimpinan;
use App\Models\Visitor;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Beranda extends Component
{
  use HalamanSitus;

  public function render(): View
  {
    $berita = Berita::with('kategori:id,nama,slug')->latest('tanggal')->latest('id')->limit(12)->get();

    return $this->halaman('livewire.beranda', 'Beranda', [
      'sambutanKadis' => ProfilDinas::konten('sambutan-kadis'),
      'taglineSambutan' => ProfilDinas::nilai('tagline-sambutan'),
      'kadis' => ProfilPimpinan::with('pegawai.jabatan')->first()?->pegawai,
      // Tiga berita terbaru jadi sorotan, tiga berikutnya berjajar di dasar sorotan, enam sesudahnya di carousel kabar.
      'sorotan' => $berita->slice(0, 3)->values(),
      'deret' => $berita->slice(3, 3)->values(),
      'arsip' => $berita->slice(6)->values(),
      'galeri' => Galeri::latest('tanggal')->latest('id')->limit(5)->get(),
      'dokumen' => Dokumen::with('kategori:id,nama,slug')->latest()->latest('id')->limit(6)->get(),
      'total' => [
        'berita' => Berita::count(),
        'dokumen' => Dokumen::count(),
        'galeri' => Galeri::count(),
        'pengunjung' => Visitor::count(),
        'pengunjungHariIni' => Visitor::whereDate('date', today())->count(),
      ],
      // Kategori kosong disembunyikan supaya tautannya tidak membuka halaman tanpa isi.
      'kategoriBerita' => KategoriBerita::has('berita')->orderBy('nama')->get(['nama', 'slug']),
      'kategoriDokumen' => KategoriDokumen::has('dokumen')->orderBy('nama')->get(['nama', 'slug']),
    ], ['pembuka' => true]);
  }
}
