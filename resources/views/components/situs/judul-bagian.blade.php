{{-- Judul bagian gaya Komdigi: huruf kapital tebal, tautan "Lihat lainnya" di kanan. `tautan` = ['label' => ..., 'href' => ...]. --}}
@props(['judul', 'keterangan' => null, 'tautan' => null, 'id' => null, 'terang' => false])

<div class="mb-6 flex flex-wrap items-end justify-between gap-x-8 gap-y-3 lg:mb-8">
  <div>
    <h2 @if ($id) id="{{ $id }}" @endif @class(['text-lg font-bold tracking-wide text-balance uppercase lg:text-xl', $terang ? 'text-white' : 'text-biru-950 dark:text-white'])>{{ $judul }}</h2>
    @if ($keterangan)
      <p @class(['mt-2 max-w-[62ch] text-sm', $terang ? 'text-biru-100' : 'text-teks-lembut'])>{{ $keterangan }}</p>
    @endif
  </div>

  @if ($tautan)
    <a href="{{ $tautan['href'] }}" wire:navigate @class(['group inline-flex items-center gap-2.5 text-sm font-semibold', $terang ? 'text-white' : 'text-biru-600 hover:text-biru-800 dark:text-biru-300 dark:hover:text-white'])>
      <span @class(['flex size-7 items-center justify-center rounded border transition', $terang ? 'border-white/40 group-hover:bg-white group-hover:text-biru-800' : 'border-biru-300 dark:border-biru-700 group-hover:border-biru-600 group-hover:bg-biru-600 group-hover:text-white'])>
        <x-situs.ikon nama="panah-kanan" class="size-3.5" />
      </span>
      {{ $tautan['label'] }}
    </a>
  @endif
</div>
