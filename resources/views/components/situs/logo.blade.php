{{--
  Logo instansi: Lambang Kota Kendari dan tanda Komdigi, lalu nama dinas sebagai teks (bukan gambar)
  supaya tetap tajam dan berganti warna mengikuti tema.
  `varian`: terang = di atas biru (tanda Komdigi putih); gelap = di atas putih (tanda Komdigi biru tua,
  kembali putih di mode gelap). `teks` false = hanya dua lambang, mis. di nav melekat yang sempit.
--}}
@props(['varian' => 'terang', 'teks' => true, 'ukuran' => 'sedang'])
@php
  [$lambang, $komdigi, $pemisah] = match ($ukuran) {
    'kecil' => ['h-8', 'h-7', 'h-6'],
    'besar' => ['h-14', 'h-12', 'h-10'],
    default => ['h-10 lg:h-12', 'h-8 lg:h-10', 'h-8 lg:h-10'],
  };
  $terang = $varian === 'terang';
@endphp
<span {{ $attributes->merge(['class' => 'flex items-center gap-2.5 lg:gap-3']) }}>
  <img src="/img/lambang-kendari.webp" alt="Lambang Kota Kendari" class="{{ $lambang }} w-auto max-w-none shrink-0" width="202" height="192" />
  <span class="{{ $pemisah }} w-px shrink-0 {{ $terang ? 'bg-white/30' : 'bg-garis' }}" aria-hidden="true"></span>
  @if ($terang)
    <img src="/img/komdigi-putih.webp" alt="Komdigi" class="{{ $komdigi }} w-auto max-w-none shrink-0" width="271" height="192" />
  @else
    <img src="/img/komdigi-gelap.webp" alt="Komdigi" class="{{ $komdigi }} w-auto max-w-none shrink-0 dark:hidden" width="271" height="192" />
    <img src="/img/komdigi-putih.webp" alt="Komdigi" class="{{ $komdigi }} hidden w-auto max-w-none shrink-0 dark:block" width="271" height="192" />
  @endif
  @if ($teks)
    <span class="ml-1 min-w-0 leading-tight">
      <span class="block font-bold tracking-tight {{ $terang ? 'text-white' : 'text-biru-950 dark:text-white' }} text-[15px] lg:text-base">
        <span class="xl:hidden">Diskominfo</span><span class="hidden xl:inline">Dinas Komunikasi dan Informatika</span>
      </span>
      <span class="mt-0.5 block text-xs font-medium {{ $terang ? 'text-biru-200' : 'text-teks-lembut' }} lg:text-[13px]">Kota Kendari</span>
    </span>
  @endif
</span>
