{{-- Kepala halaman dalam: latar abstrak jalur sirkuit di bawah lapisan biru. Slot untuk isi tambahan (kategori, tanggal). --}}
@props(['judul', 'keterangan' => null, 'remah' => []])

<section class="relative isolate overflow-hidden bg-biru-900 text-white">
  <img src="/img/latar-sirkuit.webp" alt="" aria-hidden="true" width="2560" height="720" class="absolute inset-0 -z-20 size-full object-cover object-right" />
  <div class="absolute inset-0 -z-10 bg-linear-to-r from-biru-950/95 via-biru-900/85 to-biru-800/55"></div>

  <div class="wadah py-12 lg:py-16">
    @if ($remah)
      <nav aria-label="Remah roti" class="mb-3">
        <ol class="flex flex-wrap items-center gap-2 text-xs text-biru-200">
          <li><a href="/" wire:navigate class="hover:text-white">Beranda</a></li>
          @foreach ($remah as $r)
            <li aria-hidden="true">/</li>
            <li>
              @isset($r['href'])
                <a href="{{ $r['href'] }}" wire:navigate class="hover:text-white">{{ $r['label'] }}</a>
              @else
                <span class="text-white">{{ $r['label'] }}</span>
              @endisset
            </li>
          @endforeach
        </ol>
      </nav>
    @endif

    <h1 class="max-w-4xl text-3xl leading-tight font-bold tracking-tight text-balance lg:text-4xl">{{ $judul }}</h1>
    @if ($keterangan)
      <p class="mt-3 max-w-2xl text-biru-100">{{ $keterangan }}</p>
    @endif
    {{ $slot }}
  </div>
</section>
