{{--
  Paginasi daftar Livewire. Tiap nomor tetap tautan ber-href (bisa dirayapi dan dibuka di tab baru);
  klik biasa ditangkap Livewire lalu daftar dirender ulang tanpa memuat ulang halaman, dan layar
  digulir ke awal daftar ([data-awal-daftar]).
  Di layar sempit "Sebelumnya/Berikutnya" jadi ikon dan kotak nomor mengecil, supaya 7 tombol muat
  di lebar 320px. flex-wrap sebagai pengaman: lebih baik turun baris daripada meluber ke samping.
--}}
@php
  $halaman = $paginator->currentPage();
  $terakhir = $paginator->lastPage();
  // Tampilkan maksimal 5 nomor di sekitar halaman aktif.
  $mulai = max(1, min($halaman - 2, $terakhir - 4));
  $nomor = range($mulai, min($mulai + 4, $terakhir));
  $gulir = "(document.querySelector('[data-awal-daftar]') || document.body).scrollIntoView({ behavior: document.documentElement.hasAttribute('data-henti-animasi') ? 'auto' : 'smooth' })";
  $kelasPanah = 'flex min-h-9 min-w-9 items-center justify-center rounded border border-biru-200 text-sm transition sm:px-3 sm:py-2 dark:border-biru-800';
@endphp

@if ($paginator->hasPages())
  <nav class="mt-10 flex flex-wrap items-center justify-center gap-1.5 sm:gap-2" aria-label="Paginasi">
    <a
      @unless ($paginator->onFirstPage()) href="{{ $paginator->previousPageUrl() }}" wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $gulir }}" @endunless
      @class([$kelasPanah, $paginator->onFirstPage() ? 'pointer-events-none opacity-40' : 'cursor-pointer hover:bg-biru-50 dark:hover:bg-white/5'])
      aria-disabled="{{ $paginator->onFirstPage() ? 'true' : 'false' }}"
      aria-label="Halaman sebelumnya"
    >
      <x-situs.ikon nama="chevron" class="size-4 rotate-90 sm:hidden" />
      <span class="max-sm:hidden">Sebelumnya</span>
    </a>

    @foreach ($nomor as $n)
      <a
        href="{{ $paginator->url($n) }}"
        wire:click.prevent="gotoPage({{ $n }}, '{{ $paginator->getPageName() }}')"
        x-on:click="{{ $gulir }}"
        wire:key="paginasi-{{ $n }}"
        @if ($n === $halaman) aria-current="page" @endif
        @class([
          'flex min-h-9 min-w-9 items-center justify-center rounded border px-2 text-sm tabular-nums transition sm:min-w-10 sm:px-3 sm:py-2',
          $n === $halaman ? 'border-biru-600 bg-biru-600 font-semibold text-white' : 'border-biru-200 hover:bg-biru-50 dark:border-biru-800 dark:hover:bg-white/5',
        ])
      >
        {{ $n }}
      </a>
    @endforeach

    <a
      @if ($paginator->hasMorePages()) href="{{ $paginator->nextPageUrl() }}" wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $gulir }}" @endif
      @class([$kelasPanah, $paginator->hasMorePages() ? 'cursor-pointer hover:bg-biru-50 dark:hover:bg-white/5' : 'pointer-events-none opacity-40'])
      aria-disabled="{{ $paginator->hasMorePages() ? 'false' : 'true' }}"
      aria-label="Halaman berikutnya"
    >
      <x-situs.ikon nama="chevron" class="size-4 -rotate-90 sm:hidden" />
      <span class="max-sm:hidden">Berikutnya</span>
    </a>
  </nav>
@endif
