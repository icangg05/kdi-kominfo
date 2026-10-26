@use('App\Support\Situs')

<div>
  <x-situs.judul-halaman
    :judul="$isi['judul']"
    :keterangan="$isi['ringkas']"
    :remah="[['label' => 'Layanan'], ['label' => $isi['judul']]]"
  >
    @if ($bisaDiajukan)
      <div class="mt-7 flex flex-wrap items-center gap-3">
        <a href="#ajukan" class="inline-flex items-center gap-2 rounded bg-white px-5 py-3 text-sm font-semibold text-biru-800 transition hover:bg-biru-50 active:translate-y-px">
          <x-situs.ikon nama="unggah" class="size-4" />
          Ajukan sekarang
        </a>
        <a href="/layanan/cek-pengajuan" wire:navigate class="inline-flex items-center gap-2 rounded border border-white/30 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
          <x-situs.ikon nama="cari" class="size-4" />
          Cek status pengajuan
        </a>
      </div>
    @endif
  </x-situs.judul-halaman>

  <div class="wadah py-12">
    <section class="grid items-center gap-10 lg:grid-cols-2">
      <div>
        <h2 class="mb-4 text-2xl font-bold text-biru-900 dark:text-white">{{ $isi['bagian']['judul'] }}</h2>
        @foreach ($isi['bagian']['paragraf'] ?? [] as $p)
          <p class="mb-4 leading-relaxed text-teks-lembut">{{ $p }}</p>
        @endforeach

        @isset($isi['bagian']['poin'])
          <ul class="mt-6 space-y-3 rounded bg-biru-50 p-6 dark:bg-malam-kartu">
            @foreach ($isi['bagian']['poin'] as $p)
              <li class="flex gap-3 text-sm text-teks-lembut">
                <x-situs.ikon nama="panah-kanan" class="mt-0.5 size-4 shrink-0 text-biru-600 dark:text-biru-400" />
                {{ $p }}
              </li>
            @endforeach
          </ul>
        @endisset
      </div>

      <img src="{{ $isi['gambar'] }}" alt="" class="mx-auto w-full max-w-md rounded object-contain" />
    </section>

    @isset($isi['fitur'])
      <section class="mt-16">
        <h2 class="mb-6 text-xl font-bold text-biru-900 dark:text-white">Fitur yang Tersedia</h2>
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($isi['fitur'] as $f)
            <li class="bayangan-kartu flex items-center gap-3 rounded border border-garis bg-white px-5 py-4 text-sm dark:bg-malam-kartu">
              <x-situs.ikon nama="panah-kanan" class="size-4 shrink-0 text-biru-600 dark:text-biru-400" />
              {{ $f }}
            </li>
          @endforeach
        </ul>
      </section>
    @endisset

    @isset($isi['keunggulan'])
      <section class="mt-16">
        <h2 class="mb-6 text-xl font-bold text-biru-900 dark:text-white">Keunggulan Layanan</h2>
        <div class="grid gap-5 md:grid-cols-3">
          @foreach ($isi['keunggulan'] as $k)
            <div class="bayangan-kartu rounded border border-garis bg-white p-6 dark:bg-malam-kartu">
              <h3 class="mb-2 font-bold text-biru-900 dark:text-white">{{ $k['judul'] }}</h3>
              <p class="text-sm text-teks-lembut">{{ $k['isi'] }}</p>
            </div>
          @endforeach
        </div>
      </section>
    @endisset

    <div class="mt-16 grid gap-8 lg:grid-cols-2">
      @isset($isi['syarat'])
        <section class="bayangan-kartu rounded border border-garis bg-white p-7 dark:bg-malam-kartu">
          <h2 class="mb-5 text-xl font-bold text-biru-900 dark:text-white">Persyaratan Pengajuan</h2>
          <ol class="space-y-3">
            @foreach ($isi['syarat'] as $i => $s)
              <li class="flex gap-3 text-sm text-teks-lembut">
                <span class="flex size-6 shrink-0 items-center justify-center rounded bg-biru-50 text-xs font-bold text-biru-700 dark:bg-biru-900 dark:text-biru-200">{{ $i + 1 }}</span>
                {{ $s }}
              </li>
            @endforeach
          </ol>
        </section>
      @endisset

      @isset($isi['prosedur'])
        <section class="motif-komdigi bayangan-kartu rounded bg-biru-800 p-7 text-white">
          <h2 class="mb-5 text-xl font-bold">Prosedur Pengajuan</h2>
          <ol class="space-y-3">
            @foreach ($isi['prosedur'] as $i => $p)
              <li class="flex gap-3 text-sm text-biru-100">
                <span class="flex size-6 shrink-0 items-center justify-center rounded bg-white/15 text-xs font-bold">{{ $i + 1 }}</span>
                {{ $p }}
              </li>
            @endforeach
          </ol>
        </section>
      @endisset
    </div>

    @if ($bisaDiajukan)
      <livewire:layanan.form-pengajuan :layanan="$nama" />
    @endif

    <!-- Kontak diambil dari pengaturan admin supaya tidak perlu diubah di dua tempat. -->
    <section class="bayangan-kartu mt-16 rounded border border-garis bg-biru-50/60 p-8 dark:bg-malam-kartu">
      <h2 class="mb-2 text-xl font-bold text-biru-900 dark:text-white">Hubungi Kami</h2>
      <p class="mb-6 text-sm text-teks-lembut">Untuk pertanyaan seputar layanan ini, silakan hubungi:</p>

      <div class="flex flex-wrap gap-x-10 gap-y-4 text-sm">
        @if ($pengaturan['email'] ?? null)
          <a href="mailto:{{ $pengaturan['email'] }}" class="flex items-center gap-2 font-semibold text-biru-700 hover:underline dark:text-biru-300">
            <x-situs.ikon nama="amplop" class="size-5" />
            {{ $pengaturan['email'] }}
          </a>
        @endif
        @if ($pengaturan['telp'] ?? null)
          <a href="{{ Situs::tel($pengaturan['telp']) }}" class="flex items-center gap-2 font-semibold text-biru-700 hover:underline dark:text-biru-300">
            <x-situs.ikon nama="telepon" class="size-5" />
            {{ $pengaturan['telp'] }}
          </a>
        @endif
        @if ($pengaturan['alamat'] ?? null)
          <span class="flex items-center gap-2 text-teks-lembut">
            <x-situs.ikon nama="gedung" class="size-5 shrink-0" />
            {{ $pengaturan['alamat'] }}
          </span>
        @endif
      </div>
    </section>
  </div>
</div>
