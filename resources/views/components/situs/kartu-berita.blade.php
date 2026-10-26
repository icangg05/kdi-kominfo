@props(['berita'])
@use('App\Support\Situs')

<article {{ $attributes->merge(['class' => 'kartu-timbul group relative flex h-full flex-col overflow-hidden hover:-translate-y-0.5']) }}>
  <div class="relative aspect-16/10 overflow-hidden bg-biru-100 dark:bg-biru-900">
    <img
      src="{{ Situs::url($berita->thumbnail) ?? Situs::GAMBAR_DEFAULT }}"
      alt=""
      loading="lazy"
      onerror="{{ Situs::CADANGAN_GAMBAR }}"
      class="size-full object-cover transition duration-500 group-hover:scale-[1.04]"
    />
  </div>

  <div class="flex flex-1 flex-col p-4">
    <h3 class="line-clamp-3 font-semibold leading-snug text-biru-950 dark:text-white">
      <a href="/berita/{{ $berita->slug }}" wire:navigate class="transition-colors after:absolute after:inset-0 group-hover:text-biru-600 dark:group-hover:text-biru-300">{{ $berita->judul }}</a>
    </h3>

    <p class="mt-auto flex flex-wrap items-center gap-x-2 gap-y-1 pt-3 text-xs text-teks-lembut">
      @if ($berita->kategori)
        <span class="font-semibold text-biru-600 dark:text-biru-300">{{ $berita->kategori->nama }}</span>
      @endif
      <span class="flex items-center gap-1">
        <x-situs.ikon nama="kalender" class="size-3.5" />
        {{ Situs::tanggal($berita->tanggal) }}
      </span>
    </p>
  </div>
</article>
