{{-- Pilihan Foto / Video di atas daftar galeri. --}}
<nav aria-label="Jenis galeri" class="mb-8 flex gap-2">
  @foreach ([['kunci' => 'foto', 'label' => 'Foto', 'href' => '/galeri', 'ikon' => 'foto'], ['kunci' => 'video', 'label' => 'Video', 'href' => '/galeri/video', 'ikon' => 'putar']] as $t)
    <a
      href="{{ $t['href'] }}" wire:navigate
      @if ($aktif === $t['kunci']) aria-current="page" @endif
      @class([
        'inline-flex items-center gap-2 rounded border px-4 py-2 text-sm font-semibold transition',
        $aktif === $t['kunci'] ? 'border-biru-600 bg-biru-600 text-white' : 'border-garis bg-white text-biru-800 hover:border-biru-300 hover:bg-langit dark:bg-malam-kartu dark:text-biru-100 dark:hover:border-biru-700',
      ])
    >
      <x-situs.ikon :nama="$t['ikon']" class="size-4" />
      {{ $t['label'] }}
    </a>
  @endforeach
</nav>
