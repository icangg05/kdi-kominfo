{{--
  Bilah informasi, bilah identitas (logo, pencarian, 112), dan navigasi utama desktop.
  `survei`: tautan survei kepuasan, null bila dimatikan di Pengaturan Situs admin.
  `admin`: pengunjung sedang login di panel admin, jadi tampilkan pintasan ke /admin.
  Tautan memakai wire:navigate.hover: halaman tujuan sudah diambil saat kursor berhenti di atas tautan, jadi klik terasa instan.
--}}
@props(['telp' => null, 'email' => null, 'survei' => null, 'admin' => false])
@use('App\Support\Konten')
@use('App\Support\Situs')
@use('App\Support\WaktuWita')
@php
  $jalurAktif = '/' . ltrim(request()->path(), '/');
  $aktif = fn (string $href) => $href === '/' ? $jalurAktif === '/' : str_starts_with($jalurAktif, implode('/', array_slice(explode('/', $href), 0, 2)));

  // Jam dirender dulu di server supaya bilah tidak kosong sebelum skrip berjalan.
  $sekarang = WaktuWita::sekarang();
@endphp

<!-- Bilah informasi: jam WITA di kiri, kontak di kanan. -->
<div class="bg-biru-950 text-biru-200">
  <div class="wadah flex h-9 items-center justify-between gap-4 text-xs">
    <p class="flex items-center gap-2.5">
      <x-situs.ikon nama="jam" class="size-4 text-biru-300" />
      <span class="text-sm font-semibold text-white tabular-nums"><time data-jam>{{ $sekarang->format('H.i.s') }}</time> WITA</span>
      <span class="hidden h-3 w-px bg-white/20 sm:block" aria-hidden="true"></span>
      <span class="hidden sm:inline" data-sapaan>{{ WaktuWita::sapaan($sekarang) }}</span>
    </p>
    <div class="flex items-center gap-6">
      @if ($telp)
        <a href="{{ Situs::tel($telp) }}" class="hidden items-center gap-2 transition hover:text-white md:flex">
          <x-situs.ikon nama="telepon" class="size-4" />
          {{ $telp }}
        </a>
      @endif
      @if ($email)
        <a href="mailto:{{ $email }}" class="hidden items-center gap-2 transition hover:text-white lg:flex">
          <x-situs.ikon nama="amplop" class="size-4" />
          {{ $email }}
        </a>
      @endif
    </div>
  </div>
</div>

<!-- Bilah identitas: Lambang Kota Kendari dan tanda Komdigi bertulisan putih butuh latar biru. -->
<header data-kepala class="sticky top-0 z-50 bg-biru-800 text-white shadow-[0_8px_24px_-16px_rgba(7,31,61,0.8)] lg:static lg:shadow-none">
  <div class="wadah flex h-[72px] items-center gap-6 lg:h-20">
    <a href="/" wire:navigate.hover class="shrink-0" aria-label="Beranda Diskominfo Kota Kendari">
      <x-situs.logo />
    </a>

    <form method="get" action="/berita" role="search" class="relative ml-4 hidden max-w-xl flex-1 lg:block" data-cari>
      <label for="cari-situs" class="sr-only">Cari berita</label>
      <input
        id="cari-situs"
        name="search"
        type="search"
        autocomplete="off"
        placeholder="Cari berita Diskominfo Kota Kendari"
        class="h-11 w-full rounded border border-white/10 bg-white pr-12 pl-4 text-sm text-teks placeholder:text-teks-lembut focus:outline-2 focus:outline-biru-300 dark:border-white/15 dark:bg-biru-950"
      />
      <button type="submit" class="absolute top-1 right-1 flex size-9 items-center justify-center rounded bg-biru-600 text-white transition hover:bg-biru-700" aria-label="Cari">
        <x-situs.ikon nama="cari" class="size-5" />
      </button>
    </form>

    <div class="ml-auto flex items-center gap-2">
      @if ($survei)
        <button
          type="button"
          class="hidden items-center gap-2 rounded border border-white/25 px-3.5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10 active:translate-y-px sm:flex"
          data-buka-survei
        >
          <x-situs.ikon nama="obrolan" class="size-4" />
          Survei Kepuasan
        </button>
      @endif
      <a
        href="tel:112"
        class="flex items-center gap-2 rounded bg-darurat px-3.5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#b8241a] active:translate-y-px"
      >
        <x-situs.ikon nama="telepon" class="size-4" />
        <span class="sm:hidden">112</span>
        <span class="hidden sm:inline">Hubungi 112</span>
      </a>

      <button
        type="button"
        class="group relative rounded p-2 text-white transition hover:bg-white/10 lg:hidden"
        aria-label="Buka menu"
        aria-expanded="false"
        aria-controls="menu-seluler"
        data-tombol-menu
      >
        <x-situs.ikon nama="menu" class="size-6 transition duration-300 group-aria-expanded:rotate-90 group-aria-expanded:opacity-0" />
        <x-situs.ikon nama="silang" class="absolute inset-2 size-6 -rotate-90 opacity-0 transition duration-300 group-aria-expanded:rotate-0 group-aria-expanded:opacity-100" />
      </button>
    </div>
  </div>

  <!-- Menu seluler: pencarian di atas, lalu daftar datar dengan submenu terbuka. Tertutup sendiri saat pindah
       halaman (wire:navigate) atau dengan Escape. Tinggi dianimasikan lewat grid-rows 0fr→1fr; `invisible` menjaga
       tautan tak terjangkau Tab saat tertutup. -->
  <nav id="menu-seluler" class="invisible grid grid-rows-[0fr] border-white/15 opacity-0 transition-all duration-300 ease-out data-terbuka:visible data-terbuka:grid-rows-[1fr] data-terbuka:border-t data-terbuka:opacity-100 lg:hidden" aria-label="Navigasi seluler">
    <div class="min-h-0 overflow-y-auto">
      <div class="wadah space-y-3 py-4">
        <form method="get" action="/berita" role="search" class="relative" data-cari>
          <label for="cari-seluler" class="sr-only">Cari berita</label>
          <input id="cari-seluler" name="search" type="search" autocomplete="off" placeholder="Cari berita" class="h-11 w-full rounded bg-white pr-12 pl-4 text-sm text-teks placeholder:text-teks-lembut dark:bg-biru-950" />
          <button type="submit" class="absolute top-1 right-1 flex size-9 items-center justify-center rounded bg-biru-600 text-white" aria-label="Cari">
            <x-situs.ikon nama="cari" class="size-5" />
          </button>
        </form>
        @if ($survei)
          <button type="button" class="flex w-full items-center gap-2 rounded border border-white/25 px-3 py-2.5 text-sm font-semibold hover:bg-white/10" data-buka-survei>
            <x-situs.ikon nama="obrolan" class="size-4" />
            Survei Kepuasan
          </button>
        @endif
        <ul class="space-y-1">
          @foreach (Konten::MENU_UTAMA as $item)
            <li>
              <a href="{{ $item['href'] }}" wire:navigate.hover class="block rounded px-3 py-2.5 text-sm font-semibold tracking-wide uppercase hover:bg-white/10">{{ $item['label'] }}</a>
              @isset($item['anak'])
                <ul class="mb-2 ml-3 border-l border-white/20 pl-3">
                  @foreach ($item['anak'] as $anak)
                    <li>
                      <a href="{{ $anak['href'] }}" wire:navigate.hover class="block rounded px-3 py-2 text-sm text-biru-100 hover:bg-white/10 hover:text-white">{{ $anak['label'] }}</a>
                    </li>
                  @endforeach
                </ul>
              @endisset
            </li>
          @endforeach
        </ul>
        @if ($admin)
          <a href="/admin" class="flex items-center gap-2 rounded border border-white/25 px-3 py-2.5 text-sm font-semibold hover:bg-white/10">
            <x-situs.ikon nama="orang" class="size-4" />
            Halaman Admin
          </a>
        @endif
      </div>
    </div>
  </nav>
</header>

<!-- Navigasi utama desktop: bilah putih yang menempel saat menggulir.
     Saat melekat, kedua lambang muncul di kiri menu (tanda Komdigi versi biru tua). -->
<nav class="group/nav sticky top-0 z-50 hidden border-b border-garis bg-white shadow-[0_6px_18px_-14px_rgba(7,31,61,0.45)] dark:bg-malam transition-shadow duration-300 data-melekat:shadow-[0_10px_28px_-14px_rgba(7,31,61,0.5)] lg:block" aria-label="Navigasi utama" data-nav>
  <div class="wadah flex h-[52px] items-stretch">
    <a
      href="/" wire:navigate.hover
      class="flex max-w-0 shrink-0 items-center overflow-hidden opacity-0 transition-all duration-300 group-data-melekat/nav:mr-6 group-data-melekat/nav:max-w-40 group-data-melekat/nav:opacity-100"
      tabindex="-1"
      aria-hidden="true"
    >
      <x-situs.logo varian="gelap" ukuran="kecil" :teks="false" class="gap-2!" />
    </a>
    @foreach (Konten::MENU_UTAMA as $item)
      <div class="group relative flex">
        <a
          href="{{ $item['href'] }}" wire:navigate.hover
          @class([
            'relative flex items-center gap-1 px-4 text-[13px] font-semibold tracking-wide uppercase transition-colors after:absolute after:inset-x-4 after:bottom-0 after:h-[3px] after:bg-biru-600 after:transition-transform hover:text-biru-600 dark:after:bg-biru-400 dark:hover:text-white',
            $aktif($item['href']) ? 'text-biru-600 after:scale-x-100 dark:text-biru-300' : 'text-biru-950 after:scale-x-0 hover:after:scale-x-100 dark:text-biru-100',
          ])
          @if ($aktif($item['href'])) aria-current="page" @endif
        >
          {{ $item['label'] }}
          @isset($item['anak'])
            <x-situs.ikon nama="chevron" class="size-3.5 transition group-hover:rotate-180" />
          @endisset
        </a>

        @isset($item['anak'])
          <div class="invisible absolute left-0 top-full w-64 translate-y-1 opacity-0 transition group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100">
            <ul class="overflow-hidden rounded border border-garis bg-white py-1.5 shadow-[0_18px_40px_-16px_rgba(7,31,61,0.35)] dark:bg-malam-kartu">
              @foreach ($item['anak'] as $anak)
                <li>
                  <a
                    href="{{ $anak['href'] }}" wire:navigate.hover
                    @class([
                      'block px-4 py-2.5 text-sm transition hover:bg-langit hover:text-biru-600 dark:hover:text-white',
                      $jalurAktif === $anak['href'] ? 'bg-langit font-semibold text-biru-600 dark:text-biru-300' : 'font-medium text-teks',
                    ])
                  >
                    {{ $anak['label'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        @endisset
      </div>
    @endforeach
    @if ($admin)
      <div class="ml-auto flex items-center pl-4">
        <a href="/admin" class="flex items-center gap-2 text-[13px] font-semibold tracking-wide text-biru-600 uppercase transition-colors hover:text-biru-800 dark:text-biru-300 dark:hover:text-white">
          <x-situs.ikon nama="orang" class="size-4" />
          Halaman Admin
        </a>
      </div>
    @endif
  </div>
</nav>

<!-- m-auto wajib: preflight Tailwind menghapus margin bawaan yang memusatkan <dialog>. -->
@if ($survei)
  <dialog
    id="dialog-survei"
    aria-labelledby="judul-survei"
    class="dialog-halus m-auto h-[min(52rem,calc(100dvh-2rem))] max-h-none w-[min(56rem,calc(100%-2rem))] max-w-none rounded bg-white p-0 text-teks shadow-[0_24px_48px_-28px_rgba(7,31,61,0.7)] dark:bg-malam-kartu"
  >
    <div class="flex h-full flex-col">
      <div class="flex items-center gap-3 border-b border-garis py-2 pr-2 pl-4">
        <h2 id="judul-survei" class="text-sm font-bold tracking-wide text-biru-950 uppercase dark:text-white">Survei Kepuasan Masyarakat</h2>
        <a href="{{ $survei }}" target="_blank" rel="noopener" class="ml-auto hidden items-center gap-1.5 text-sm font-semibold text-biru-600 hover:underline sm:flex dark:text-biru-300">
          <x-situs.ikon nama="tautan" class="size-4" />
          Buka di tab baru
        </a>
        <form method="dialog" class="ml-auto sm:ml-0">
          <button class="flex size-10 items-center justify-center rounded transition hover:bg-langit" aria-label="Tutup survei">
            <x-situs.ikon nama="silang" class="size-5" />
          </button>
        </form>
      </div>
      <iframe data-src="{{ $survei }}" title="Survei Kepuasan Masyarakat" class="min-h-0 w-full flex-1 bg-white"></iframe>
    </div>
  </dialog>
@endif
