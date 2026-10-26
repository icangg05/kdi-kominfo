<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;

/** Komponen halaman penuh situs publik: dirender di dalam layout components.layouts.situs. */
trait HalamanSitus
{
  /**
   * @param  array<string, mixed>  $data  Variabel untuk view komponen.
   * @param  array<string, mixed>  $meta  Prop tambahan layout: deskripsi, gambar, jenis, terbit, noindex.
   */
  protected function halaman(string $view, string $judul, array $data = [], array $meta = []): View
  {
    return view($view, $data)->layout('components.layouts.situs', ['judul' => $judul, ...$meta]);
  }
}
