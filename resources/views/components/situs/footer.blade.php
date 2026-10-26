@props(['telp' => null, 'email' => null, 'alamat' => null, 'fb' => null, 'ig' => null, 'tt' => null, 'yt' => null])
@use('App\Support\Konten')
@use('App\Support\Situs')
@php
  $sosial = array_filter([
    ['url' => $fb, 'nama' => 'facebook', 'label' => 'Facebook'],
    ['url' => $ig, 'nama' => 'instagram', 'label' => 'Instagram'],
    ['url' => $yt, 'nama' => 'youtube', 'label' => 'YouTube'],
    ['url' => $tt, 'nama' => 'tiktok', 'label' => 'TikTok'],
  ], fn ($s) => filled($s['url']));
@endphp

<footer class="motif-komdigi bg-biru-900 text-biru-100">
  <div class="wadah grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-[1.4fr_repeat(3,1fr)] lg:py-16">
    <div>
      <x-situs.logo ukuran="besar" :teks="false" />
      <p class="mt-5 max-w-sm text-sm leading-relaxed">
        <strong class="font-semibold text-white">Dinas Komunikasi dan Informatika Kota Kendari.</strong>
        Penyelenggara layanan informasi publik dan infrastruktur TIK Pemerintah Kota Kendari.
      </p>
    </div>

    @foreach (Konten::TAUTAN_FOOTER as $kolom)
      <nav aria-label="{{ $kolom['judul'] }}">
        <h2 class="mb-4 text-sm font-bold tracking-wide text-white uppercase">{{ $kolom['judul'] }}</h2>
        <ul class="space-y-3 text-sm">
          @foreach ($kolom['item'] as $item)
            <li>
              <a href="{{ $item['href'] }}" wire:navigate class="transition hover:text-white hover:underline hover:underline-offset-4">{{ $item['label'] }}</a>
            </li>
          @endforeach
        </ul>
      </nav>
    @endforeach
  </div>

  <div class="border-t border-white/10">
    <div class="wadah flex flex-col gap-5 py-7 text-sm lg:flex-row lg:items-center lg:justify-between">
      @if ($alamat)
        <p class="flex items-start gap-3">
          <x-situs.ikon nama="gedung" class="mt-0.5 size-5 shrink-0 text-biru-300" />
          <span>{{ $alamat }}</span>
        </p>
      @endif

      <div class="flex flex-wrap items-center gap-x-8 gap-y-3">
        @if ($telp)
          <a href="{{ Situs::tel($telp) }}" class="flex items-center gap-2.5 font-semibold text-white hover:underline hover:underline-offset-4">
            <x-situs.ikon nama="telepon" class="size-5 shrink-0 text-biru-300" />
            {{ $telp }}
          </a>
        @endif

        @if ($email)
          <a href="mailto:{{ $email }}" class="flex items-center gap-2.5 font-semibold text-white hover:underline hover:underline-offset-4">
            <x-situs.ikon nama="amplop" class="size-5 shrink-0 text-biru-300" />
            {{ $email }}
          </a>
        @endif

        @if ($sosial)
          <div class="flex items-center gap-2">
            @foreach ($sosial as $s)
              <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $s['label'] }}" class="flex size-9 items-center justify-center rounded border border-white/20 transition hover:bg-white hover:text-biru-900">
                <x-situs.ikon :nama="$s['nama']" class="size-4" />
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="bg-biru-950">
    <p class="wadah py-5 text-xs text-biru-200">
      &copy; {{ now()->year }} Dinas Komunikasi dan Informatika Kota Kendari. Seluruh hak cipta dilindungi.
    </p>
  </div>
</footer>

<!-- Tombol kembali ke atas: muncul setelah penanda 400px keluar dari layar. -->
<span data-penanda-atas class="pointer-events-none absolute top-100 left-0 size-px" aria-hidden="true"></span>
<button
  type="button"
  data-ke-atas
  hidden
  class="fixed right-5 bottom-5 z-40 rounded bg-biru-600 p-3 text-white shadow-[0_12px_28px_-10px_rgba(7,31,61,0.6)] transition hover:bg-biru-700"
  aria-label="Kembali ke atas"
>
  <x-situs.ikon nama="panah-atas" class="size-5" />
</button>
