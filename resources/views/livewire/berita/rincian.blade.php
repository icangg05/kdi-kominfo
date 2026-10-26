@use('App\Support\Situs')
@php
  $u = urlencode($tautan);
  $j = urlencode($berita->judul);

  $bagikan = [
    ['nama' => 'WhatsApp', 'ikon' => 'whatsapp', 'href' => "https://wa.me/?text={$j}%20{$u}"],
    ['nama' => 'Facebook', 'ikon' => 'facebook', 'href' => "https://www.facebook.com/sharer/sharer.php?u={$u}"],
    ['nama' => 'X', 'ikon' => 'x', 'href' => "https://x.com/intent/post?text={$j}&url={$u}"],
    ['nama' => 'Telegram', 'ikon' => 'telegram', 'href' => "https://t.me/share/url?url={$u}&text={$j}"],
  ];

  // JSON_HEX_TAG: judul berisi "</script>" tidak bisa keluar dari blok JSON-LD.
  $jsonLd = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $berita->judul,
    'description' => $berita->ringkasan(),
    'image' => [Situs::asal() . ($sampul ?? '/img/og-default.jpg')],
    'datePublished' => $berita->tanggal?->toDateString(),
    'mainEntityOfPage' => $tautan,
    'articleSection' => $berita->kategori?->nama,
    'author' => ['@type' => 'GovernmentOrganization', 'name' => 'Diskominfo Kota Kendari', 'url' => Situs::asal()],
    'publisher' => [
      '@type' => 'GovernmentOrganization',
      'name' => 'Dinas Komunikasi dan Informatika Kota Kendari',
      'logo' => ['@type' => 'ImageObject', 'url' => Situs::asal() . '/img/lambang-kendari.webp'],
    ],
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
@endphp

<div>
  <x-slot:head>
    <script type="application/ld+json">{!! $jsonLd !!}</script>
  </x-slot:head>

  <article>
    <x-situs.judul-halaman :judul="$berita->judul" :remah="[['label' => 'Berita', 'href' => '/berita']]">
      <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-3 text-sm text-biru-200">
        @if ($berita->kategori)
          <a href="/berita?kategori={{ $berita->kategori->slug }}" wire:navigate class="rounded bg-white/15 px-3 py-1 text-xs font-semibold text-white hover:bg-white/25">
            {{ $berita->kategori->nama }}
          </a>
        @endif
        <span class="flex items-center gap-2"><x-situs.ikon nama="kalender" class="size-4" />{{ Situs::tanggal($berita->tanggal) }}</span>
        <span class="flex items-center gap-2"><x-situs.ikon nama="mata" class="size-4" />{{ Situs::angka($berita->total_lihat) }} kali dibaca</span>
      </div>
      <!-- Ruang untuk sampul yang menumpang ke pita judul. -->
      <div class="h-10 lg:h-14" aria-hidden="true"></div>
    </x-situs.judul-halaman>

    <div class="wadah py-12">
      <div class="grid gap-12 lg:grid-cols-[1fr_320px]">
        <div data-perbesar>
          <!-- Sampul menumpang ke pita judul dengan bingkai, supaya tidak terbaca sebagai foto isi berita.
               Gambar utuh (contain) di depan salinannya yang diburamkan: foto potret tidak terpotong.
               Tanpa sampul (atau berkasnya hilang dari disk): sampul bawaan sebagai hiasan, tidak bisa diperbesar. -->
          <figure class="relative z-10 -mt-[5.5rem] mb-10 aspect-16/9 overflow-hidden rounded bg-biru-950 shadow-[0_24px_48px_-28px_rgba(7,31,61,0.7)] ring-4 ring-white lg:-mt-[6.5rem] dark:ring-malam">
            @if ($sampul)
              <img src="{{ $sampul }}" alt="" aria-hidden="true" class="absolute inset-0 size-full scale-110 object-cover opacity-60 blur-2xl" />
              <img src="{{ $sampul }}" alt="{{ $berita->judul }}" fetchpriority="high" tabindex="0" class="relative size-full cursor-zoom-in object-contain" />
            @else
              <img src="{{ Situs::GAMBAR_DEFAULT }}" alt="" aria-hidden="true" fetchpriority="high" class="size-full object-cover" />
            @endif
          </figure>
          <div class="prosa max-w-none">{!! $berita->konten !!}</div>
          @if ($berita->wp_url)
            <p class="mt-6 text-sm text-teks-lembut">
              Sumber:
              <a href="{{ $berita->wp_url }}" target="_blank" rel="noopener" class="font-semibold text-biru-600 hover:underline dark:text-biru-300">Portal Berita Kota Kendari</a>
            </p>
          @endif

          <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-garis pt-6">
            <p class="mr-2 text-sm font-semibold text-biru-900 dark:text-white">Bagikan berita ini</p>
            @foreach ($bagikan as $b)
              <a
                href="{{ $b['href'] }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Bagikan ke {{ $b['nama'] }}"
                title="{{ $b['nama'] }}"
                class="grid size-10 place-items-center rounded bg-biru-50 text-biru-700 transition-colors hover:bg-biru-600 hover:text-white active:scale-[0.97] dark:bg-malam-kartu dark:text-biru-200 dark:hover:bg-biru-600 dark:hover:text-white"
              >
                <x-situs.ikon :nama="$b['ikon']" class="size-4.5" />
              </a>
            @endforeach
            <button
              type="button"
              data-salin="{{ $tautan }}"
              class="flex h-10 items-center gap-2 rounded border border-garis px-3 text-sm font-medium text-biru-700 transition-colors hover:border-biru-600 hover:text-biru-600 active:scale-[0.97] dark:text-biru-200 dark:hover:text-white"
            >
              <x-situs.ikon nama="tautan" class="size-4.5" />
              <span aria-live="polite">Salin tautan</span>
            </button>
          </div>
        </div>

        <!-- Menempel tepat di bawah nav desktop (52px) selagi isi berita digulir. -->
        @if ($lainnya->isNotEmpty())
          <aside class="lg:sticky lg:top-[76px] lg:self-start">
            <h2 class="mb-5 border-b border-garis pb-3 font-bold text-biru-900 dark:text-white">Berita Lainnya</h2>
            <ul class="space-y-5">
              @foreach ($lainnya as $b)
                <li class="flex gap-4">
                  <a href="/berita/{{ $b->slug }}" wire:navigate class="size-20 shrink-0 overflow-hidden rounded bg-biru-50 dark:bg-biru-900">
                    <img src="{{ Situs::url($b->thumbnail) ?? Situs::GAMBAR_DEFAULT }}" alt="" loading="lazy" onerror="{{ Situs::CADANGAN_GAMBAR }}" class="size-full object-cover" />
                  </a>
                  <div>
                    <a href="/berita/{{ $b->slug }}" wire:navigate class="line-clamp-2 text-sm font-semibold text-biru-900 hover:text-biru-600 dark:text-biru-100 dark:hover:text-white">
                      {{ $b->judul }}
                    </a>
                    <p class="mt-1 text-xs text-teks-lembut">{{ Situs::tanggal($b->tanggal) }}</p>
                  </div>
                </li>
              @endforeach
            </ul>
          </aside>
        @endif
      </div>
    </div>
  </article>

  <x-situs.dialog-gambar />
</div>
