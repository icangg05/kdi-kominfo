@use('App\Support\Situs')

<div>
  <x-situs.judul-halaman
    judul="Galeri Video"
    keterangan="Rekaman kegiatan dan informasi layanan Diskominfo Kota Kendari."
    :remah="[['label' => 'Galeri', 'href' => '/galeri'], ['label' => 'Video']]"
  />

  <div class="wadah scroll-mt-16 py-12" data-awal-daftar>
    @include('livewire.galeri.tab', ['aktif' => 'video'])

    @if ($daftar->isNotEmpty())
      <div class="grid gap-6 transition-opacity sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-60">
        @foreach ($daftar as $v)
          @php $youtubeId = $v->youtubeId(); @endphp
          <article class="group bayangan-kartu relative aspect-video overflow-hidden rounded bg-biru-900" wire:key="video-{{ $v->id }}">
            @if ($youtubeId)
              <img src="https://i.ytimg.com/vi/{{ $youtubeId }}/hqdefault.jpg" alt="" loading="lazy" class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105" />
            @endif
            <!-- Scrim sama dengan kartu foto: pekat di area teks supaya terbaca di thumbnail terang. -->
            <div class="absolute inset-x-0 bottom-0 flex items-center gap-3 bg-linear-to-t from-biru-950 via-biru-950/85 via-55% to-transparent p-4 pt-16 text-white">
              <span class="flex size-10 shrink-0 items-center justify-center rounded bg-white text-biru-700 transition group-hover:scale-110">
                <x-situs.ikon nama="putar" class="size-5" />
              </span>
              <div class="min-w-0">
                <h2 class="line-clamp-2 text-sm leading-snug font-semibold tracking-wide uppercase">{{ $v->judul }}</h2>
                <p class="mt-1 text-xs text-biru-200">{{ Situs::tanggal($v->tanggal) }}</p>
              </div>
            </div>
            <!-- Tautan menutup seluruh kartu dan ditaruh paling akhir supaya klik dan cincin fokusnya di atas keterangan.
                 Tanpa JavaScript tautan membuka YouTube; dengan JavaScript video diputar di dialog. -->
            <a href="{{ $v->tautan }}" target="_blank" rel="noopener noreferrer" class="absolute inset-0 focus-visible:-outline-offset-2" @if ($youtubeId) data-video="{{ $youtubeId }}" @endif data-judul="{{ $v->judul }}" data-tanggal="{{ Situs::tanggal($v->tanggal) }}">
              <span class="sr-only">Putar video {{ $v->judul }}</span>
            </a>
          </article>
        @endforeach
      </div>
      {{ $daftar->links('livewire.paginasi') }}
    @else
      <x-situs.kosong pesan="Belum ada video di galeri." />
    @endif
  </div>

  <dialog id="dialog-video" aria-label="Pemutar video" class="dialog-halus m-auto w-[min(64rem,calc(100vw-2rem))] max-w-none overflow-visible bg-transparent p-0 [--pekat:0.92]" wire:ignore>
    <div class="aspect-video overflow-hidden rounded bg-black" data-pemutar></div>
    <div class="pt-3 text-white">
      <p class="line-clamp-2 leading-snug font-semibold" data-judul></p>
      <p class="mt-1 text-sm text-biru-200" data-tanggal></p>
    </div>
    <!-- Tombol tutup di atas video supaya tidak menutupi kontrol YouTube di pojok kanan atas.
         autofocus: tanpa itu fokus jatuh ke iframe YouTube dan tombol Escape tertelan olehnya. -->
    <form method="dialog">
      <button autofocus class="absolute right-0 bottom-full mb-2 flex size-10 items-center justify-center rounded bg-biru-950/70 text-white transition hover:bg-biru-950" aria-label="Tutup video">
        <x-situs.ikon nama="silang" class="size-5" />
      </button>
    </form>
  </dialog>
</div>
