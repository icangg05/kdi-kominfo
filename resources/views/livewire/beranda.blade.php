@use('App\Support\Konten')
@use('App\Support\Situs')
@php
  $darurat = Konten::LAYANAN['telpon-darurat-112'];
@endphp

<div>
  <x-slot:head>
    <script type="application/ld+json">{!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'GovernmentOrganization',
      'name' => 'Dinas Komunikasi dan Informatika Kota Kendari',
      'alternateName' => 'Diskominfo Kota Kendari',
      'url' => Situs::asal(),
      'logo' => Situs::asal() . '/img/lambang-kendari.webp',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
  </x-slot:head>

  <h1 class="sr-only">Dinas Komunikasi dan Informatika Kota Kendari</h1>

  <!-- Sorotan berita -->
  <section class="relative isolate overflow-hidden bg-biru-950 text-white" aria-roledescription="carousel" aria-label="Sorotan berita" data-sorotan>
    @if ($sorotan->isEmpty())
      <img src="/img/latar-jaringan.webp" alt="" aria-hidden="true" width="2560" height="1440" fetchpriority="high" class="absolute inset-0 -z-20 size-full object-cover" />
    @endif
    @foreach ($sorotan as $i => $b)
      <img
        src="{{ Situs::url($b->thumbnail) ?? Situs::GAMBAR_DEFAULT }}"
        alt=""
        aria-hidden="true"
        fetchpriority="{{ $i === 0 ? 'high' : 'low' }}"
        onerror="{{ Situs::CADANGAN_GAMBAR }}"
        @class(['absolute inset-0 -z-20 size-full object-cover transition-opacity duration-700', 'opacity-0' => $i !== 0])
        data-latar
      />
    @endforeach
    <div class="absolute inset-0 -z-10 bg-linear-to-r from-biru-950 via-biru-900/90 to-biru-800/40"></div>

    <div class="wadah grid min-h-[26rem] content-center gap-8 py-12 lg:min-h-[32rem] lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center lg:py-16">
      <div>
        @if ($sorotan->isEmpty())
          <div class="max-w-3xl">
            <p class="text-[2rem] leading-[1.12] font-bold tracking-tight text-balance sm:text-5xl">Dinas Komunikasi dan Informatika Kota Kendari</p>
            <p class="mt-4 max-w-2xl text-biru-100 lg:text-lg">Layanan informasi publik dan infrastruktur TIK Pemerintah Kota Kendari.</p>
          </div>
        @endif

        @foreach ($sorotan as $i => $b)
          <article class="max-w-3xl" @if ($i !== 0) hidden @endif aria-roledescription="slide" aria-label="{{ $i + 1 }} dari {{ $sorotan->count() }}" data-slide>
            <p class="flex flex-wrap items-center gap-3 text-sm text-biru-100">
              @if ($b->kategori)
                <a href="/berita?kategori={{ $b->kategori->slug }}" wire:navigate class="rounded bg-biru-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-biru-500">{{ $b->kategori->nama }}</a>
              @endif
              <span>{{ Situs::tanggal($b->tanggal) }}</span>
            </p>
            <h2 class="mt-4 line-clamp-3 text-2xl leading-tight font-bold tracking-tight text-balance sm:text-3xl lg:text-4xl">
              <a href="/berita/{{ $b->slug }}" wire:navigate class="decoration-2 underline-offset-4 hover:underline">{{ $b->judul }}</a>
            </h2>
            <p class="mt-4 line-clamp-2 max-w-2xl text-biru-100 lg:text-lg">{{ $b->ringkasan() }}</p>
            <a href="/berita/{{ $b->slug }}" wire:navigate class="mt-7 inline-flex items-center gap-2 rounded bg-white px-5 py-3 text-sm font-semibold text-biru-800 transition hover:bg-biru-50 active:translate-y-px">
              Baca selengkapnya
              <x-situs.ikon nama="panah-kanan" class="size-4" />
            </a>
          </article>
        @endforeach
      </div>

      @if ($sorotan->count() > 1)
        <div class="flex gap-2 lg:flex-col" role="group" aria-label="Pilih sorotan">
          @foreach ($sorotan as $i => $b)
            <button
              type="button"
              aria-label="Tampilkan sorotan {{ $i + 1 }}"
              aria-current="{{ $i === 0 ? 'true' : 'false' }}"
              class="relative flex size-10 items-center justify-center overflow-hidden rounded border border-white/35 text-sm font-semibold tabular-nums transition hover:bg-white/10 aria-[current=true]:border-white aria-[current=true]:bg-white aria-[current=true]:text-biru-800"
              data-ke="{{ $i }}"
            >
              {{ $i + 1 }}
            </button>
          @endforeach
        </div>
      @endif
    </div>

    @if ($deret->isNotEmpty())
      <div class="border-t border-white/15 bg-biru-950/70">
        <ul class="wadah tanpa-scrollbar flex snap-x snap-mandatory scroll-px-4 gap-5 overflow-x-auto sm:grid sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-white/15">
          @foreach ($deret as $b)
            <li class="group relative flex w-[78%] shrink-0 snap-start items-center gap-3 py-4 sm:w-auto lg:px-5 lg:first:pl-0">
              <span class="size-14 shrink-0 overflow-hidden rounded bg-biru-700">
                <img src="{{ Situs::url($b->thumbnail) ?? Situs::GAMBAR_DEFAULT }}" alt="" loading="lazy" onerror="{{ Situs::CADANGAN_GAMBAR }}" class="size-full object-cover" />
              </span>
              <div class="min-w-0">
                <h3 class="line-clamp-2 text-sm leading-snug font-semibold">
                  <a href="/berita/{{ $b->slug }}" wire:navigate class="after:absolute after:inset-0 group-hover:underline">{{ $b->judul }}</a>
                </h3>
                <p class="mt-1 text-xs text-biru-200">{{ Situs::tanggal($b->tanggal) }}</p>
              </div>
            </li>
          @endforeach
          <li class="flex shrink-0 snap-start items-center py-4 pr-4 lg:px-5">
            <a href="/berita" wire:navigate class="group inline-flex items-center gap-3 text-sm font-semibold">
              <span class="flex size-9 items-center justify-center rounded border border-white/40 transition group-hover:bg-white group-hover:text-biru-800">
                <x-situs.ikon nama="panah-kanan" class="size-4" />
              </span>
              Semua berita
            </a>
          </li>
        </ul>
      </div>
    @endif
  </section>

  <!-- Sambutan Kepala Dinas. Kiri: kartu identitas pimpinan (tautan ke profil) dengan pernik bentuk, yaitu
       bidang langit yang menjulur ke tepi layar, blok biru yang tergeser, dan dua siku bingkai. Kanan: kalimat
       teks sambutan (prosa seragam), lalu tagline. Gerak masuk mengikuti gulir, lihat situs.css. -->
  @if ($sambutanKadis)
    <section class="sambutan relative isolate overflow-x-clip pt-14 lg:pt-20" aria-labelledby="judul-sambutan">
      <div @class(['wadah grid items-start', 'lg:grid-cols-[17rem_minmax(0,1fr)] lg:gap-x-16 xl:grid-cols-[19rem_minmax(0,1fr)] xl:gap-x-20' => $kadis])>
        <div @class(['lg:col-start-2' => $kadis])>
          <x-situs.judul-bagian id="judul-sambutan" judul="Sambutan Kepala Dinas" />
        </div>

        @if ($kadis)
          <div class="group/potret relative mr-3 mb-10 lg:col-start-1 lg:row-span-2 lg:row-start-1 lg:mt-2 lg:mb-0">
            <div class="sambutan-bidang absolute top-20 right-1/3 -bottom-8 -left-[50vw] -z-10 hidden bg-langit lg:block" aria-hidden="true"></div>
            <span class="sambutan-siku absolute -top-4 -left-4 hidden size-12 border-t-2 border-l-2 border-biru-300 transition-[translate] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover/potret:-translate-1 lg:block dark:border-biru-700" aria-hidden="true"></span>
            <span class="sambutan-siku sambutan-siku-bawah absolute -right-7 -bottom-7 hidden size-12 border-r-2 border-b-2 border-biru-300 transition-[translate] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover/potret:translate-1 lg:block dark:border-biru-700" aria-hidden="true"></span>

            <a href="/profil-dinas/profil-pimpinan" wire:navigate class="relative block rounded">
              <span class="sambutan-geser absolute inset-0 translate-3 rounded bg-biru-600 transition-[translate] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover/potret:translate-4.5" aria-hidden="true"></span>
              <span class="sambutan-kartu relative grid grid-cols-[6.5rem_minmax(0,1fr)] overflow-hidden rounded bg-biru-800 text-white transition-[translate] duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover/potret:-translate-1.5 group-active/potret:translate-0 lg:grid-cols-1">
                <span class="block overflow-hidden bg-biru-900">
                  <img
                    src="{{ Situs::url($kadis->foto) ?? Situs::GAMBAR_DEFAULT }}"
                    alt=""
                    loading="lazy"
                    onerror="{{ Situs::CADANGAN_GAMBAR }}"
                    class="sambutan-foto aspect-4/5 size-full object-cover transition-[scale] duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover/potret:scale-105"
                  />
                </span>
                <span class="motif-komdigi flex items-center gap-4 p-4 lg:p-5">
                  <span class="min-w-0 flex-1">
                    <span class="sr-only">Profil pimpinan: </span>
                    <span class="block leading-snug font-bold text-balance">{{ $kadis->nama }}</span>
                    @if ($kadis->jabatan)
                      <span class="mt-1 block text-sm text-biru-200">{{ $kadis->jabatan->nama }}</span>
                    @endif
                  </span>
                  <span class="flex size-9 shrink-0 items-center justify-center rounded border border-white/40 transition group-hover/potret:border-white group-hover/potret:bg-white group-hover/potret:text-biru-800" aria-hidden="true">
                    <x-situs.ikon nama="panah-kanan" class="size-4 transition-[translate] duration-300 group-hover/potret:translate-x-0.5" />
                  </span>
                </span>
              </span>
            </a>
          </div>
        @endif

        <div @class(['lg:col-start-2' => $kadis])>
          <div class="relative isolate lg:mt-2">
            <span class="sambutan-kutip pointer-events-none absolute -top-8 -left-1 -z-10 text-[8rem] leading-none font-bold text-biru-100 select-none lg:-top-10 lg:-left-7 lg:text-[10rem] dark:text-biru-900" aria-hidden="true">“</span>
            <div class="sambutan-teks prosa max-w-[65ch] *:last:mb-0 lg:text-lg">{!! $sambutanKadis !!}</div>
          </div>

          @if ($taglineSambutan)
            <ul class="mt-9 flex flex-wrap gap-2" aria-label="Tagline Diskominfo Kota Kendari">
              @foreach ($taglineSambutan as $i => $t)
                <li
                  class="sambutan-tagline rounded border border-biru-200 bg-biru-50 px-3.5 py-2 text-xs font-bold tracking-wide text-biru-800 uppercase dark:border-biru-800 dark:bg-biru-900/50 dark:text-biru-100"
                  style="--mulai: {{ 40 + $i * 6 }}%; --akhir: {{ 62 + $i * 6 }}%"
                >
                  {{ $t }}
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </section>
  @endif

  <!-- Layanan -->
  <section class="py-14 lg:py-20" aria-labelledby="judul-layanan">
    <div class="wadah">
      <div class="rounded bg-linear-to-br from-biru-900 via-biru-700 to-biru-600 px-5 py-8 text-white shadow-[0_24px_48px_-28px_rgba(7,31,61,0.7)] sm:px-8 lg:px-10 lg:py-10">
        <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-3">
          <h2 id="judul-layanan" class="text-xl font-bold text-balance lg:text-2xl">Layanan Diskominfo untuk Perangkat Daerah dan Warga</h2>
          <a href="/layanan/cek-pengajuan" wire:navigate class="group inline-flex items-center gap-2.5 text-sm font-semibold">
            <span class="flex size-7 items-center justify-center rounded border border-white/40 transition group-hover:bg-white group-hover:text-biru-800">
              <x-situs.ikon nama="cari" class="size-3.5" />
            </span>
            Cek status pengajuan
          </a>
        </div>
        <ul class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4">
          @foreach (Konten::LAYANAN_UTAMA as $l)
            <li class="flex">
              <a href="/layanan/{{ $l['slug'] }}" wire:navigate class="group flex w-full flex-col rounded border border-white/15 bg-white/10 p-5 transition hover:-translate-y-1 hover:border-white/35 hover:bg-white/15">
                <span @class(['flex size-12 items-center justify-center rounded', $l['slug'] === 'telpon-darurat-112' ? 'bg-darurat text-white' : 'bg-white text-biru-700'])>
                  <x-situs.ikon :nama="$l['ikon']" class="size-6" />
                </span>
                <h3 class="mt-5 text-sm font-bold tracking-wide uppercase">{{ $l['judul'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-biru-100">{{ $l['ringkas'] }}</p>
                <span class="mt-auto flex items-center gap-3 pt-6">
                  <span class="flex size-9 items-center justify-center rounded border border-white/40 transition group-hover:bg-white group-hover:text-biru-700">
                    <x-situs.ikon nama="panah-kanan" class="size-4" />
                  </span>
                  @if ($l['slug'] !== 'telpon-darurat-112')
                    <span class="text-xs font-semibold text-biru-100">Ajukan daring</span>
                  @endif
                </span>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </section>

  <!-- Pencarian dokumen publik -->
  <section class="pb-14 lg:pb-20" aria-labelledby="judul-cari-dokumen">
    <div class="wadah">
      <div class="grid overflow-hidden rounded border border-garis lg:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">
        <div class="flex items-center gap-5 bg-langit p-6 sm:p-8 lg:p-10">
          <span class="flex size-16 shrink-0 items-center justify-center rounded bg-biru-600 text-white lg:size-20">
            <x-situs.ikon nama="berkas" class="size-8 lg:size-10" />
          </span>
          <div>
            <p class="text-2xl leading-tight font-bold text-biru-900 lg:text-3xl dark:text-white">Dokumen Publik</p>
            <p class="mt-1 text-sm text-teks-lembut">Laporan, peraturan, dan dokumen resmi yang dapat diunduh bebas.</p>
          </div>
        </div>

        <div class="bg-biru-800 p-6 text-white sm:p-8 lg:p-10">
          <h2 id="judul-cari-dokumen" class="text-2xl font-bold">Telusuri {{ Situs::angka($total['dokumen']) }} dokumen publik</h2>
          <p class="mt-1 text-sm text-biru-200">Ketik judul dokumen yang Anda cari.</p>
          <form method="get" action="/dokumen" role="search" class="relative mt-5" data-cari>
            <label for="cari-dokumen" class="sr-only">Cari dokumen</label>
            <input id="cari-dokumen" name="search" type="search" autocomplete="off" placeholder="Judul dokumen" class="h-12 w-full rounded bg-white pr-14 pl-4 text-teks placeholder:text-teks-lembut focus:outline-2 focus:outline-biru-300 dark:bg-biru-950" />
            <button type="submit" class="absolute top-1.5 right-1.5 flex size-9 items-center justify-center rounded bg-biru-600 transition hover:bg-biru-700" aria-label="Cari dokumen">
              <x-situs.ikon nama="cari" class="size-5" />
            </button>
          </form>
          @if ($kategoriDokumen->isNotEmpty())
            <p class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
              <span class="text-biru-200">Kategori:</span>
              @foreach ($kategoriDokumen as $k)
                <a href="/dokumen?kategori={{ $k->slug }}" wire:navigate class="font-medium underline decoration-white/30 underline-offset-4 transition hover:decoration-white">{{ $k->nama }}</a>
              @endforeach
            </p>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- Profil dan bidang dinas -->
  <section class="relative isolate overflow-hidden bg-biru-950 py-16 text-white lg:py-24" aria-labelledby="judul-profil">
    <img src="/img/bg-earth.webp" alt="" aria-hidden="true" loading="lazy" class="absolute inset-y-0 right-0 -z-10 h-full w-full object-cover opacity-40 lg:w-3/5" />
    <div class="absolute inset-0 -z-10 bg-linear-to-r from-biru-950 via-biru-950/85 to-biru-950/10"></div>
    <div class="kisi-titik absolute inset-0 -z-10 opacity-60" aria-hidden="true"></div>

    <div class="wadah">
      <div class="max-w-2xl">
        <h2 id="judul-profil" class="text-3xl leading-tight font-bold text-balance lg:text-[2.75rem]">Transformasi Digital Pemerintah Kota Kendari</h2>
        <p class="mt-5 max-w-[60ch] leading-relaxed text-biru-100 lg:text-lg">
          Diskominfo Kota Kendari mengelola informasi publik, menyelenggarakan pemerintahan berbasis elektronik,
          serta menyediakan infrastruktur TIK dan persandian bagi Pemerintah Kota Kendari.
        </p>
        <a href="/profil-dinas/tentang-kami" wire:navigate class="group mt-7 inline-flex items-center gap-3 text-sm font-semibold">
          <span class="flex size-9 items-center justify-center rounded border border-white/40 transition group-hover:bg-white group-hover:text-biru-900">
            <x-situs.ikon nama="panah-kanan" class="size-4" />
          </span>
          Baca selengkapnya
        </a>
      </div>

      <ul class="mt-14 grid gap-8 border-t border-white/15 pt-10 sm:grid-cols-2 lg:mt-20 lg:grid-cols-4 lg:gap-6">
        @foreach (Konten::BIDANG_DINAS as $b)
          <li class="flex gap-4">
            <span class="flex size-12 shrink-0 items-center justify-center rounded bg-biru-600/50 text-biru-100 ring-1 ring-white/20">
              <x-situs.ikon :nama="$b['ikon']" class="size-6" />
            </span>
            <div>
              <h3 class="text-sm leading-snug font-bold tracking-wide uppercase">{{ $b['judul'] }}</h3>
              <p class="mt-2 text-sm leading-relaxed text-biru-200">{{ $b['isi'] }}</p>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  </section>

  <!-- Galeri dan angka -->
  <section class="py-14 lg:py-20" aria-labelledby="judul-galeri">
    <div class="wadah">
      <x-situs.judul-bagian id="judul-galeri" judul="Kegiatan dan Informasi Publik" :tautan="['label' => 'Lihat lainnya', 'href' => '/galeri']" />

      <div class="grid gap-4 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:gap-4" data-perbesar>
          @foreach ($galeri as $g)
            <li>
              <figure class="group relative aspect-4/3 overflow-hidden rounded bg-biru-900">
                <img src="{{ Situs::url($g->gambar) ?? Situs::GAMBAR_DEFAULT }}" alt="{{ $g->judul }}" data-judul="{{ $g->judul }}" data-tanggal="{{ Situs::tanggal($g->tanggal) }}" loading="lazy" onerror="{{ Situs::CADANGAN_GAMBAR }}" tabindex="0" class="absolute inset-0 size-full cursor-zoom-in object-cover transition duration-500 group-hover:scale-105 focus-visible:-outline-offset-2" />
                <figcaption class="pointer-events-none absolute inset-x-0 bottom-0 bg-linear-to-t from-biru-950 via-biru-950/70 to-transparent p-3 pt-10 text-white">
                  <p class="line-clamp-2 text-xs leading-snug font-semibold tracking-wide uppercase">{{ $g->judul }}</p>
                  <p class="mt-1 text-[11px] text-biru-200">{{ Situs::tanggal($g->tanggal) }}</p>
                </figcaption>
              </figure>
            </li>
          @endforeach
          <li>
            <a href="/galeri" wire:navigate class="group flex aspect-4/3 flex-col justify-between rounded border border-garis bg-langit p-4 text-biru-800 transition hover:border-biru-300 hover:bg-biru-100 dark:text-biru-100 dark:hover:border-biru-700 dark:hover:bg-biru-900">
              <x-situs.ikon nama="foto" class="size-7" />
              <span>
                <span class="block text-3xl font-bold tabular-nums">{{ Situs::angka($total['galeri']) }}</span>
                <span class="mt-1 flex items-center gap-2 text-sm font-semibold">
                  Foto kegiatan
                  <x-situs.ikon nama="panah-kanan" class="size-4 transition group-hover:translate-x-1" />
                </span>
              </span>
            </a>
          </li>
        </ul>

        <div class="flex flex-col rounded bg-toska p-6 text-white lg:p-8">
          <h3 class="text-sm font-semibold">Total kunjungan situs</h3>
          <p class="mt-2 text-6xl leading-none font-bold tracking-tight tabular-nums lg:text-7xl">{{ Situs::angka($total['pengunjung']) }}</p>
          <p class="mt-3 text-sm">{{ Situs::angka($total['pengunjungHariIni']) }} pengunjung hari ini</p>
          <dl class="mt-8 divide-y divide-white/20 border-t border-white/20 text-sm lg:mt-auto">
            <div class="flex items-baseline justify-between gap-4 py-3">
              <dt>Berita diterbitkan</dt>
              <dd class="text-xl font-bold tabular-nums">{{ Situs::angka($total['berita']) }}</dd>
            </div>
            <div class="flex items-baseline justify-between gap-4 py-3">
              <dt>Dokumen publik</dt>
              <dd class="text-xl font-bold tabular-nums">{{ Situs::angka($total['dokumen']) }}</dd>
            </div>
            <div class="flex items-baseline justify-between gap-4 pt-3">
              <dt>Foto kegiatan</dt>
              <dd class="text-xl font-bold tabular-nums">{{ Situs::angka($total['galeri']) }}</dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </section>

  <!-- Kabar lainnya: geser mendatar -->
  @if ($arsip->isNotEmpty())
    <section class="bg-langit py-14 lg:py-20" aria-labelledby="judul-kabar" data-geser>
      <div class="wadah">
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4 lg:mb-8">
          <h2 id="judul-kabar" class="text-lg font-bold tracking-wide text-biru-950 uppercase lg:text-xl dark:text-white">Kabar Diskominfo Kota Kendari</h2>
          <div class="flex items-center gap-2">
            <a href="/berita" wire:navigate class="mr-3 text-sm font-semibold text-biru-600 hover:text-biru-800 dark:text-biru-300 dark:hover:text-white">Semua berita</a>
            <button type="button" class="flex size-10 items-center justify-center rounded border border-biru-300 bg-white text-biru-700 transition hover:border-biru-600 hover:bg-biru-600 hover:text-white dark:border-biru-700 dark:bg-malam-kartu dark:text-biru-100" aria-label="Geser ke kiri" data-arah="-1">
              <x-situs.ikon nama="panah-kiri" class="size-4" />
            </button>
            <button type="button" class="flex size-10 items-center justify-center rounded border border-biru-300 bg-white text-biru-700 transition hover:border-biru-600 hover:bg-biru-600 hover:text-white dark:border-biru-700 dark:bg-malam-kartu dark:text-biru-100" aria-label="Geser ke kanan" data-arah="1">
              <x-situs.ikon nama="panah-kanan" class="size-4" />
            </button>
          </div>
        </div>

        <ul class="tanpa-scrollbar -mx-4 flex snap-x snap-mandatory scroll-px-4 gap-4 overflow-x-auto px-4 pb-2 lg:mx-0 lg:scroll-px-0 lg:px-0" data-jalur>
          @foreach ($arsip as $b)
            <li class="w-[78%] shrink-0 snap-start sm:w-[calc((100%-1rem)/2)] lg:w-[calc((100%-3rem)/4)]">
              <x-situs.kartu-berita :berita="$b" />
            </li>
          @endforeach
        </ul>
      </div>
    </section>
  @endif

  <!-- Dokumen terbaru, dengan topik berita dan Kendari Siaga 112 di bilah samping -->
  <section class="py-14 lg:py-20">
    <div @class(['wadah grid gap-12', 'lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]' => $dokumen->isNotEmpty()])>
      @if ($dokumen->isNotEmpty())
        <div>
          <x-situs.judul-bagian id="judul-dokumen-terbaru" judul="Dokumen Publik Terbaru" :tautan="['label' => 'Lihat lainnya', 'href' => '/dokumen']" />
          <ul class="grid gap-4 max-sm:[&>li:nth-child(n+4)]:hidden sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($dokumen as $d)
              <li class="kartu-timbul group relative flex flex-col p-5">
                <div class="flex items-start justify-between gap-3">
                  <span class="flex h-12 w-10 items-center justify-center rounded bg-biru-600 text-[11px] font-bold text-white uppercase">{{ $d->ekstensi() ?: 'file' }}</span>
                  <span class="text-xs text-teks-lembut tabular-nums">{{ Situs::angka($d->total_unduhan) }} unduhan</span>
                </div>
                <h3 class="mt-4 line-clamp-2 font-semibold leading-snug text-biru-950 dark:text-white">{{ $d->judul }}</h3>
                @if ($d->kategori)
                  <p class="mt-1 text-xs font-semibold text-biru-600 dark:text-biru-300">{{ $d->kategori->nama }}</p>
                @endif
                <p class="mt-2 line-clamp-2 text-sm text-teks-lembut">{{ $d->deskripsi }}</p>
                <!-- Seluruh kartu membuka rincian; tautan unduh diangkat di atas bidang klik itu. -->
                <div class="mt-auto flex items-center justify-between gap-3 pt-4 text-sm font-semibold">
                  <button type="button" data-dokumen="{{ $d->rincian() }}" class="inline-flex items-center gap-2 text-biru-600 after:absolute after:inset-0 hover:text-biru-800 dark:text-biru-300 dark:hover:text-white">
                    <x-situs.ikon nama="mata" class="size-4" />
                    Lihat rincian
                  </button>
                  <a @if ($d->urlUnduh()) href="{{ $d->urlUnduh() }}" @endif aria-disabled="{{ $d->urlUnduh() ? 'false' : 'true' }}" class="relative z-10 -mr-2 inline-flex items-center gap-1.5 rounded px-2 py-1 text-teks-lembut transition hover:bg-biru-50 hover:text-biru-800 aria-disabled:pointer-events-none aria-disabled:opacity-40 dark:hover:bg-white/5 dark:hover:text-white">
                    <x-situs.ikon nama="unduh" class="size-4" />
                    Unduh
                  </a>
                </div>
              </li>
            @endforeach
          </ul>
        </div>
      @endif

      <aside class="space-y-6">
        @if ($kategoriBerita->isNotEmpty())
          <section class="rounded bg-linear-to-br from-nila to-biru-700 p-6 text-white" aria-labelledby="judul-topik">
            <h2 id="judul-topik" class="text-lg font-bold tracking-wide uppercase">Pilih Topik Berita</h2>
            <p class="mt-2 text-sm text-biru-100">Baca berita Diskominfo Kota Kendari sesuai topik yang Anda minati.</p>
            <ul class="mt-5 flex flex-wrap gap-2">
              @foreach ($kategoriBerita as $k)
                <li>
                  <a href="/berita?kategori={{ $k->slug }}" wire:navigate class="group inline-flex items-center gap-1.5 rounded border border-white/25 bg-white/10 px-3 py-1.5 text-sm font-medium transition hover:bg-white hover:text-nila">
                    {{ $k->nama }}
                    <x-situs.ikon nama="panah-kanan" class="size-3.5 transition group-hover:translate-x-0.5" />
                  </a>
                </li>
              @endforeach
            </ul>
          </section>
        @endif

        <section class="motif-komdigi rounded bg-biru-900 p-6 text-white" aria-labelledby="judul-darurat">
          <div class="flex items-center gap-3">
            <span class="flex size-11 items-center justify-center rounded bg-darurat">
              <x-situs.ikon nama="telepon" class="size-5" />
            </span>
            <h2 id="judul-darurat" class="text-lg font-bold">Kendari Siaga 112</h2>
          </div>
          <p class="mt-3 text-sm leading-relaxed text-biru-100">{{ $darurat['ringkas'] }}</p>
          <ul class="mt-5 space-y-2">
            <li>
              <a href="tel:112" class="flex items-center justify-between rounded bg-white px-4 py-3 text-sm font-semibold text-biru-900 transition hover:bg-biru-50">
                Hubungi 112
                <x-situs.ikon nama="telepon" class="size-4" />
              </a>
            </li>
            <li>
              <a href="/layanan/telpon-darurat-112" wire:navigate class="flex items-center justify-between rounded border border-white/25 px-4 py-3 text-sm font-semibold transition hover:bg-white/10">
                Tentang layanan 112
                <x-situs.ikon nama="panah-kanan" class="size-4" />
              </a>
            </li>
          </ul>
        </section>
      </aside>
    </div>
  </section>

  <!-- Tautan terkait -->
  <nav class="border-t border-garis bg-white py-8 dark:bg-malam" aria-label="Tautan terkait">
    <ul class="wadah grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-2 lg:flex lg:items-center lg:justify-between">
      @foreach (Konten::TAUTAN_TERKAIT as $t)
        <li>
          <a href="{{ $t['href'] }}" target="_blank" rel="noopener noreferrer" class="group inline-flex items-center gap-2 text-sm font-semibold text-biru-800 transition hover:text-biru-600 dark:text-biru-100 dark:hover:text-white">
            {{ $t['label'] }}
            <x-situs.ikon nama="panah-kanan" class="size-3.5 -rotate-45 transition group-hover:translate-x-0.5" />
          </a>
        </li>
      @endforeach
    </ul>
  </nav>

  <x-situs.dialog-gambar />
  <x-situs.dialog-dokumen />
</div>
