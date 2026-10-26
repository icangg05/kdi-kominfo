<div>
  <x-situs.judul-halaman
    judul="Berita"
    keterangan="Kabar terbaru seputar kegiatan, layanan, dan kebijakan Diskominfo Kota Kendari."
    :remah="[['label' => 'Berita']]"
  />

  <div class="wadah scroll-mt-16 py-12" data-awal-daftar>
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <!-- Filter kategori: tautan ber-href (jalan tanpa JavaScript), diambil alih Livewire saat diklik. -->
      <div class="flex flex-wrap gap-2">
        <a
          href="/berita{{ $search !== '' ? '?search=' . urlencode($search) : '' }}"
          wire:click.prevent="pilihKategori('')"
          @if ($kategori === '') aria-current="page" @endif
          @class([
            'rounded border px-4 py-1.5 text-sm transition',
            $kategori === '' ? 'border-biru-600 bg-biru-600 font-semibold text-white' : 'border-biru-200 hover:bg-biru-50 dark:border-biru-800 dark:hover:bg-white/5',
          ])
        >
          Semua
        </a>
        @foreach ($kategoriBerita as $k)
          <a
            href="/berita?{{ http_build_query(array_filter(['search' => $search, 'kategori' => $k->slug])) }}"
            wire:click.prevent="pilihKategori('{{ $k->slug }}')"
            wire:key="kategori-{{ $k->slug }}"
            @if ($kategori === $k->slug) aria-current="page" @endif
            @class([
              'rounded border px-4 py-1.5 text-sm transition',
              $kategori === $k->slug ? 'border-biru-600 bg-biru-600 font-semibold text-white' : 'border-biru-200 hover:bg-biru-50 dark:border-biru-800 dark:hover:bg-white/5',
            ])
          >
            {{ $k->nama }}
          </a>
        @endforeach
      </div>

      <form method="get" action="/berita" class="relative w-full lg:w-72" role="search" wire:submit="$refresh">
        @if ($kategori)
          <input type="hidden" name="kategori" value="{{ $kategori }}" />
        @endif
        <label for="cari-berita" class="sr-only">Cari berita</label>
        <input
          id="cari-berita"
          type="search"
          autocomplete="off"
          name="search"
          wire:model.live.debounce.400ms="search"
          placeholder="Cari berita…"
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
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          @foreach ($daftar as $b)
            <x-situs.kartu-berita :berita="$b" wire:key="berita-{{ $b->id }}" />
          @endforeach
        </div>
        {{ $daftar->links('livewire.paginasi') }}
      @else
        <x-situs.kosong :pesan="trim($search) !== '' ? 'Tidak ada berita yang cocok dengan “' . trim($search) . '”.' : 'Belum ada berita di kategori ini.'" />
      @endif
    </div>
  </div>
</div>
