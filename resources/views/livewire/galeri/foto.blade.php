@use('App\Support\Situs')

<div>
  <x-situs.judul-halaman
    judul="Galeri Foto"
    keterangan="Dokumentasi kegiatan dan layanan Diskominfo Kota Kendari."
    :remah="[['label' => 'Galeri'], ['label' => 'Foto']]"
  />

  <div class="wadah scroll-mt-16 py-12" data-awal-daftar>
    @include('livewire.galeri.tab', ['aktif' => 'foto'])

    @if ($daftar->isNotEmpty())
      <div class="grid gap-6 transition-opacity sm:grid-cols-2 lg:grid-cols-3" data-perbesar wire:loading.class="opacity-60">
        @foreach ($daftar as $f)
          <figure class="group bayangan-kartu relative aspect-4/3 overflow-hidden rounded bg-biru-900" wire:key="foto-{{ $f->id }}">
            <img src="{{ Situs::url($f->gambar) ?? Situs::GAMBAR_DEFAULT }}" alt="{{ $f->judul }}" data-judul="{{ $f->judul }}" data-tanggal="{{ Situs::tanggal($f->tanggal) }}" loading="lazy" onerror="{{ Situs::CADANGAN_GAMBAR }}" tabindex="0" class="absolute inset-0 size-full cursor-zoom-in object-cover transition duration-500 group-hover:scale-105 focus-visible:-outline-offset-2" />
            <!-- Scrim pekat (≥85%) di sepanjang area teks, baru memudar di atasnya, supaya teks tetap terbaca di foto terang. -->
            <figcaption class="pointer-events-none absolute inset-x-0 bottom-0 bg-linear-to-t from-biru-950 via-biru-950/85 via-55% to-transparent p-4 pt-16 text-white">
              <p class="line-clamp-2 text-sm leading-snug font-semibold tracking-wide uppercase">{{ $f->judul }}</p>
              <p class="mt-1 text-xs text-biru-200">{{ Situs::tanggal($f->tanggal) }}</p>
            </figcaption>
          </figure>
        @endforeach
      </div>
      {{ $daftar->links('livewire.paginasi') }}
    @else
      <x-situs.kosong pesan="Belum ada foto di galeri." />
    @endif
  </div>

  <x-situs.dialog-gambar />
</div>
