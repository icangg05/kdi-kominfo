<div>
  <x-situs.judul-halaman
    judul="Tugas dan Fungsi"
    keterangan="Tugas pokok dan fungsi Dinas Komunikasi dan Informatika Kota Kendari."
    :remah="[['label' => 'Profil Dinas'], ['label' => 'Tupoksi']]"
  />

  <div class="wadah py-12">
    @if ($tugas)
      <section class="mb-16">
        <x-situs.judul-bagian judul="Tugas" />
        <div class="prosa max-w-4xl">{!! $tugas !!}</div>
      </section>
    @endif

    @if ($fungsi)
      <section>
        <x-situs.judul-bagian judul="Fungsi" />
        <ol class="grid gap-5 md:grid-cols-2">
          @foreach ($fungsi as $i => $f)
            <li class="bayangan-kartu flex gap-4 rounded border border-garis bg-white p-5 dark:bg-malam-kartu">
              <span class="flex size-8 shrink-0 items-center justify-center rounded bg-biru-50 text-sm font-bold text-biru-700 dark:bg-biru-900 dark:text-biru-200">
                {{ $i + 1 }}
              </span>
              <p class="text-sm leading-relaxed text-teks-lembut">{{ $f }}</p>
            </li>
          @endforeach
        </ol>
      </section>
    @endif
  </div>
</div>
