{{--
  Tata letak situs publik. Dipakai komponen Livewire halaman (->layout('components.layouts.situs', [...]))
  dan halaman galat (<x-layouts.situs>).
  `gambar`: pratinjau saat tautan dibagikan; path relatif dijadikan absolut. `terbit`: ISO, hanya untuk jenis article.
  `pembuka`: tampilkan layar pembuka (x-situs.pembuka), hanya untuk beranda.
--}}
@props([
  'judul',
  'deskripsi' => 'Situs resmi Dinas Komunikasi dan Informatika Kota Kendari.',
  'gambar' => null,
  'jenis' => 'website',
  'terbit' => null,
  'noindex' => false,
  'pembuka' => false,
])
@use('App\Models\ProfilDinas')
@use('App\Support\Situs')
@php
  // Kontak dan media sosial dikelola dari panel admin, jadi diambil di setiap render.
  // Situs tetap tampil walau basis data sedang bermasalah (mis. halaman galat 500).
  try {
    $atur = ProfilDinas::pengaturan();
  } catch (\Throwable) {
    $atur = [];
  }

  $survei = ($atur['survei_aktif'] ?? false) ? ($atur['survei_url'] ?? null) : null;
  $judulPenuh = "{$judul} | Diskominfo Kota Kendari";
  $asal = Situs::asal();
  $kanonis = $asal . '/' . ltrim(request()->path(), '/');
  $gambarPenuh = str_starts_with((string) $gambar, 'http') ? $gambar : $asal . ($gambar ?: '/img/og-default.jpg');
@endphp
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" href="/img/apple-touch-icon.png" />
    <title>{{ $judulPenuh }}</title>
    <meta name="description" content="{{ $deskripsi }}" />
    <link rel="canonical" href="{{ $kanonis }}" />
    @if ($noindex)
      <meta name="robots" content="noindex" />
    @endif
    <meta name="theme-color" content="#0b2f5c" />
    <!-- Situs selalu terang. `only` juga menolak penggelapan otomatis peramban (Chrome, Samsung Internet, dll.). -->
    <meta name="color-scheme" content="only light" />

    <meta property="og:site_name" content="Diskominfo Kota Kendari" />
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="{{ $jenis }}" />
    <meta property="og:url" content="{{ $kanonis }}" />
    <meta property="og:title" content="{{ $judul }}" />
    <meta property="og:description" content="{{ $deskripsi }}" />
    <meta property="og:image" content="{{ $gambarPenuh }}" />
    <meta property="og:image:alt" content="{{ $judul }}" />
    @unless ($gambar)
      <meta property="og:image:width" content="1200" />
      <meta property="og:image:height" content="630" />
    @endunless
    @if ($jenis === 'article' && $terbit)
      <meta property="article:published_time" content="{{ $terbit }}" />
    @endif
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $judul }}" />
    <meta name="twitter:description" content="{{ $deskripsi }}" />
    <meta name="twitter:image" content="{{ $gambarPenuh }}" />
    {{ $head ?? '' }}
    <link rel="preload" href="/fonts/ibm-plex-sans-latin.woff2" as="font" type="font/woff2" crossorigin />
    <!--
      Pilihan panel Aksesibilitas dipasang sebelum halaman dilukis supaya tidak berkedip.
      Disimpan di window karena wire:navigate menyalin atribut <html> dari halaman tujuan (yang polos), jadi
      resources/js/situs.js memanggilnya lagi setiap kali halaman ditukar. Skrip ini sendiri tidak dijalankan ulang.
    -->
    <script>
      window.terapkanPreferensi = () => {
        const akar = document.documentElement;
        try {
          const a = JSON.parse(localStorage.getItem('aksesibilitas') || '{}');
          for (const k in a) if (k !== 'skala') akar.toggleAttribute('data-' + k, Boolean(a[k]));
          akar.style.fontSize = a.skala && a.skala !== 100 ? a.skala + '%' : '';
        } catch {}
      };
      terapkanPreferensi();
    </script>
    @vite(['resources/css/situs.css', 'resources/js/situs.js'])
  </head>
  <body class="flex min-h-screen flex-col">
    @if ($pembuka)
      <x-situs.pembuka :alamat="$atur['alamat'] ?? null" />
    @endif
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-3 focus:rounded focus:bg-white focus:px-4 focus:py-2 focus:text-biru-700">
      Lewati ke konten utama
    </a>

    <x-situs.header :telp="$atur['telp'] ?? null" :email="$atur['email'] ?? null" :survei="$survei" :admin="auth()->check()" />

    <main id="konten" class="flex-1">
      {{ $slot }}
    </main>

    <x-situs.footer
      :telp="$atur['telp'] ?? null"
      :email="$atur['email'] ?? null"
      :alamat="$atur['alamat'] ?? null"
      :fb="$atur['fb'] ?? null"
      :ig="$atur['ig'] ?? null"
      :tt="$atur['tt'] ?? null"
      :yt="$atur['yt'] ?? null"
    />
    <x-situs.aksesibilitas />
  </body>
</html>
