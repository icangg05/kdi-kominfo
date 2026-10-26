<div>
  <x-situs.judul-halaman
    judul="Struktur Organisasi"
    keterangan="Bagan susunan organisasi Dinas Komunikasi dan Informatika Kota Kendari."
    :remah="[['label' => 'Profil Dinas'], ['label' => 'Struktur Organisasi']]"
  />

  <div class="wadah py-12">
    @if ($adaBagan)
      <!-- Bagan dengan banyak unit digulir di dalam wadahnya sendiri, halaman tidak ikut melebar. -->
      <div class="bayangan-kartu overflow-x-auto rounded border border-garis bg-white p-4 sm:p-8 dark:bg-malam-kartu">
        <x-situs.bagan :bagan="$bagan" />
      </div>

      @if ($gambar)
        <a href="{{ $gambar }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-biru-600 hover:underline dark:text-biru-300">
          <x-situs.ikon :nama="$pdf ? 'berkas' : 'foto'" class="size-4" />
          Lihat bagan resmi ({{ $pdf ? 'PDF' : 'gambar' }})
        </a>
      @endif
    @elseif ($gambar && $pdf)
      <a href="{{ $gambar }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-biru-600 hover:underline dark:text-biru-300">
        <x-situs.ikon nama="berkas" class="size-4" />
        Lihat bagan struktur organisasi (PDF)
      </a>
    @elseif ($gambar)
      <div class="bayangan-kartu overflow-x-auto rounded border border-garis bg-white p-4 dark:bg-malam-kartu">
        <img src="{{ $gambar }}" alt="Bagan struktur organisasi Diskominfo Kota Kendari" class="mx-auto min-w-3xl" />
      </div>
    @else
      <x-situs.kosong pesan="Bagan struktur organisasi belum diisi." />
    @endif
  </div>
</div>
