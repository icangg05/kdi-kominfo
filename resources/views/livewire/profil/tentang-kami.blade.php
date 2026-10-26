@php
  // Rasio foto bebas (potret/lanskap), jadi bingkai memotong dengan object-cover dan versi utuhnya dilihat lewat perbesar.
  $susunan = [
    // 1: satu bingkai lebar.
    1 => ['wadah' => '', 'sel' => ['aspect-4/3 sm:aspect-21/9']],
    // 2: berdampingan 3:2 supaya tidak jadi dua kotak kembar.
    2 => [
      'wadah' => 'sm:grid-cols-[3fr_2fr] sm:h-88 lg:h-104',
      'sel' => ['aspect-4/3 sm:aspect-auto', 'aspect-4/3 sm:aspect-auto'],
    ],
    // 3: foto pertama besar di kiri, dua lainnya bertumpuk di kanan; di ponsel foto pertama melebar, dua sisanya berdampingan.
    3 => [
      'wadah' => 'grid-cols-2 sm:grid-cols-[3fr_2fr] sm:grid-rows-2 sm:h-104 lg:h-120',
      'sel' => ['col-span-2 aspect-4/3 sm:col-span-1 sm:row-span-2 sm:aspect-auto', 'aspect-4/3 sm:aspect-auto', 'aspect-4/3 sm:aspect-auto'],
    ],
  ][count($foto)] ?? null;
@endphp

<div>
  <x-situs.judul-halaman
    judul="Tentang Kami"
    keterangan="Sejarah, visi, dan misi Dinas Komunikasi dan Informatika Kota Kendari."
    :remah="[['label' => 'Profil Dinas'], ['label' => 'Tentang Kami']]"
  />

  <div class="wadah py-12">
    @if ($susunan)
      <div class="mb-14 grid gap-3 sm:gap-4 {{ $susunan['wadah'] }}" data-perbesar>
        @foreach ($foto as $i => $f)
          <figure class="group relative overflow-hidden rounded bg-biru-100 dark:bg-biru-900 {{ $susunan['sel'][$i] }}">
            <img
              src="{{ $f }}"
              alt="{{ count($foto) > 1 ? 'Kantor Diskominfo Kota Kendari, foto ' . ($i + 1) . ' dari ' . count($foto) : 'Kantor Diskominfo Kota Kendari' }}"
              fetchpriority="{{ $i === 0 ? 'high' : 'auto' }}"
              tabindex="0"
              class="absolute inset-0 size-full cursor-zoom-in object-cover transition duration-500 group-hover:scale-[1.03] focus-visible:-outline-offset-2 motion-reduce:transition-none motion-reduce:group-hover:scale-100"
            />
          </figure>
        @endforeach
      </div>
    @endif

    @if ($sejarah)
      <section class="mb-16">
        <x-situs.judul-bagian judul="Sejarah Singkat" />
        <div class="prosa max-w-4xl">{!! $sejarah !!}</div>
      </section>
    @endif

    <div class="grid gap-8 lg:grid-cols-2">
      @if ($visi)
        <section class="motif-komdigi bayangan-kartu rounded bg-biru-800 p-8 text-white">
          <h2 class="mb-4 text-xl font-bold">Visi</h2>
          <p class="text-lg leading-relaxed text-biru-100 italic">{{ $visi }}</p>
        </section>
      @endif

      @if ($misi)
        <section class="bayangan-kartu rounded border border-garis bg-white p-8 dark:bg-malam-kartu">
          <h2 class="mb-5 text-xl font-bold text-biru-900 dark:text-white">Misi</h2>
          <ol class="space-y-4">
            @foreach ($misi as $i => $m)
              <li class="flex gap-4">
                <span class="flex size-7 shrink-0 items-center justify-center rounded bg-biru-600 text-xs font-bold text-white">
                  {{ $i + 1 }}
                </span>
                <p class="text-sm leading-relaxed text-teks-lembut">{{ $m }}</p>
              </li>
            @endforeach
          </ol>
        </section>
      @endif
    </div>
  </div>

  <x-situs.dialog-gambar />
</div>
