@use('App\Support\Situs')

<div>
  <x-situs.judul-halaman
    judul="Profil Pegawai"
    :keterangan="Situs::angka($daftar->total()) . ' pegawai Dinas Komunikasi dan Informatika Kota Kendari.'"
    :remah="[['label' => 'Profil Dinas'], ['label' => 'Profil Pegawai']]"
  />

  <div class="wadah scroll-mt-16 py-12" data-awal-daftar>
    <form method="get" class="relative mb-8 w-full lg:w-80" role="search" wire:submit="$refresh">
      <label for="cari-pegawai" class="sr-only">Cari pegawai</label>
      <input
        id="cari-pegawai"
        type="search"
        autocomplete="off"
        name="search"
        wire:model.live.debounce.400ms="search"
        placeholder="Cari nama pegawai…"
        class="w-full rounded border border-biru-200 py-2.5 pr-11 pl-4 text-sm outline-none transition focus:border-biru-500 focus:ring-2 focus:ring-biru-200 dark:border-biru-800 dark:bg-malam-kartu dark:focus:ring-biru-800"
      />
      <span class="absolute top-1/2 right-1 -translate-y-1/2 rounded p-2 text-biru-600 dark:text-biru-300" aria-hidden="true">
        <x-situs.ikon nama="cari" class="size-4" wire:loading.remove wire:target="search" />
        <svg wire:loading wire:target="search" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3" /><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" /></svg>
      </span>
    </form>

    <div wire:loading.class="opacity-60" wire:target="search, gotoPage, nextPage, previousPage" class="transition-opacity">
      @if ($daftar->isNotEmpty())
        <ul class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-perbesar>
          @foreach ($daftar as $g)
            @php $foto = Situs::url($g->foto); @endphp
            <li class="bayangan-kartu flex gap-4 rounded border border-garis bg-white p-5 dark:bg-malam-kartu" wire:key="pegawai-{{ $g->id }}">
              <div class="size-16 shrink-0 rounded bg-biru-50 dark:bg-biru-900">
                @if ($foto)
                  <img src="{{ $foto }}" alt="Foto {{ $g->nama }}" loading="lazy" tabindex="0" class="size-full cursor-zoom-in rounded object-cover" />
                @else
                  <span class="flex size-full items-center justify-center text-biru-300 dark:text-biru-700">
                    <x-situs.ikon nama="orang" class="size-7" />
                  </span>
                @endif
              </div>

              <div class="min-w-0">
                <p class="leading-snug font-semibold text-biru-900 dark:text-white">{{ $g->nama }}</p>
                @if ($g->jabatan)
                  <p class="mt-1 text-sm text-biru-600 dark:text-biru-300">{{ $g->jabatan->nama }}</p>
                @endif
                @if ($g->nip)
                  <p class="mt-1 text-xs text-teks-lembut">NIP {{ $g->nip }}</p>
                @endif
              </div>
            </li>
          @endforeach
        </ul>
        {{ $daftar->links('livewire.paginasi') }}
      @else
        <x-situs.kosong :pesan="trim($search) !== '' ? 'Tidak ada pegawai bernama “' . trim($search) . '”.' : 'Belum ada data pegawai.'" />
      @endif
    </div>
  </div>

  <x-situs.dialog-gambar />
</div>
