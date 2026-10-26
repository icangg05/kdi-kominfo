@use('App\Support\Situs')

<div>
  <x-situs.judul-halaman
    judul="Dokumen"
    keterangan="Dokumen publik, produk hukum, dan laporan yang dapat diunduh secara bebas."
    :remah="[['label' => 'Dokumen']]"
  />

  <div class="wadah scroll-mt-16 py-12" data-awal-daftar>
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <!-- Kategori bisa banyak: satu baris yang digeser ke samping, bukan membungkus jadi beberapa baris. -->
      <nav class="relative -mx-4 flex min-w-0 gap-2 overflow-x-auto px-4 pb-2 [scrollbar-width:thin] lg:mx-0 lg:px-0" aria-label="Kategori dokumen" data-kategori>
        <a
          href="/dokumen{{ $search !== '' ? '?search=' . urlencode($search) : '' }}"
          wire:click.prevent="pilihKategori('')"
          @if ($kategori === '') aria-current="page" @endif
          @class([
            'shrink-0 rounded border px-4 py-1.5 text-sm whitespace-nowrap transition',
            $kategori === '' ? 'border-biru-600 bg-biru-600 font-semibold text-white' : 'border-biru-200 hover:bg-biru-50 dark:border-biru-800 dark:hover:bg-white/5',
          ])
        >
          Semua
        </a>
        @foreach ($kategoriDokumen as $k)
          <a
            href="/dokumen?{{ http_build_query(array_filter(['search' => $search, 'kategori' => $k->slug])) }}"
            wire:click.prevent="pilihKategori('{{ $k->slug }}')"
            wire:key="kategori-{{ $k->slug }}"
            @if ($kategori === $k->slug) aria-current="page" @endif
            @class([
              'shrink-0 rounded border px-4 py-1.5 text-sm whitespace-nowrap transition',
              $kategori === $k->slug ? 'border-biru-600 bg-biru-600 font-semibold text-white' : 'border-biru-200 hover:bg-biru-50 dark:border-biru-800 dark:hover:bg-white/5',
            ])
          >
            {{ $k->nama }}
          </a>
        @endforeach
      </nav>

      <form method="get" action="/dokumen" class="relative w-full shrink-0 lg:w-72" role="search" wire:submit="$refresh">
        @if ($kategori)
          <input type="hidden" name="kategori" value="{{ $kategori }}" />
        @endif
        <label for="cari-dokumen" class="sr-only">Cari dokumen</label>
        <input
          id="cari-dokumen"
          type="search"
          autocomplete="off"
          name="search"
          wire:model.live.debounce.400ms="search"
          placeholder="Cari dokumen…"
          class="w-full rounded border border-biru-200 py-2.5 pr-11 pl-4 text-sm outline-none transition focus:border-biru-500 focus:ring-2 focus:ring-biru-200 dark:border-biru-800 dark:bg-malam-kartu dark:focus:ring-biru-800"
        />
        <span class="absolute top-1/2 right-1 -translate-y-1/2 rounded p-2 text-biru-600 dark:text-biru-300" aria-hidden="true">
          <x-situs.ikon nama="cari" class="size-4" wire:loading.remove wire:target="search" />
          <svg wire:loading wire:target="search" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3" /><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" /></svg>
        </span>
      </form>
    </div>

    <div wire:loading.class="opacity-60" wire:target="search, pilihKategori, gotoPage, nextPage, previousPage" class="transition-opacity">
      @if ($daftar->isNotEmpty())
        <ul class="grid gap-5 md:grid-cols-2">
          @foreach ($daftar as $d)
            <li class="kartu-timbul flex gap-5 p-5" wire:key="dokumen-{{ $d->id }}">
              <span class="flex size-12 shrink-0 items-center justify-center rounded bg-biru-50 text-biru-600 dark:bg-biru-900 dark:text-biru-300">
                <x-situs.ikon nama="berkas" class="size-6" />
              </span>

              <div class="min-w-0 flex-1">
                @if ($d->kategori)
                  <p class="mb-1 text-xs font-semibold text-biru-600 dark:text-biru-300">{{ $d->kategori->nama }}</p>
                @endif
                <h2 class="leading-snug font-bold text-biru-900 dark:text-white">{{ $d->judul }}</h2>
                <p class="mt-2 line-clamp-2 text-sm text-teks-lembut">{{ $d->deskripsi }}</p>

                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-3">
                  <div class="flex flex-wrap gap-2">
                    <a
                      @if ($d->urlUnduh()) href="{{ $d->urlUnduh() }}" @endif
                      aria-disabled="{{ $d->urlUnduh() ? 'false' : 'true' }}"
                      class="inline-flex items-center gap-2 rounded bg-biru-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-biru-700 aria-disabled:pointer-events-none aria-disabled:opacity-40"
                    >
                      <x-situs.ikon nama="unduh" class="size-4" />
                      Unduh {{ strtoupper($d->ekstensi()) }}
                    </a>
                    <button
                      type="button"
                      data-dokumen="{{ $d->rincian() }}"
                      class="inline-flex items-center gap-2 rounded border border-biru-200 px-4 py-2 text-sm font-semibold text-biru-700 transition hover:bg-biru-50 dark:border-biru-800 dark:text-biru-200 dark:hover:bg-white/5"
                    >
                      <x-situs.ikon nama="mata" class="size-4" />
                      Lihat
                    </button>
                  </div>
                  <span class="text-xs text-teks-lembut">{{ Situs::angka($d->total_unduhan) }} kali diunduh</span>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
        {{ $daftar->links('livewire.paginasi') }}
      @else
        <x-situs.kosong :pesan="trim($search) !== '' ? 'Tidak ada dokumen yang cocok dengan “' . trim($search) . '”.' : 'Belum ada dokumen di kategori ini.'" />
      @endif
    </div>
  </div>

  <x-situs.dialog-dokumen />
</div>
