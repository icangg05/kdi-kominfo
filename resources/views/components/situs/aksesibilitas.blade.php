@php
  // Kunci = nama atribut data-* di <html>; gayanya ada di resources/css/situs.css.
  $opsi = [
    ['kunci' => 'kontras', 'label' => 'Kontras Tinggi', 'ikon' => 'kontras'],
    ['kunci' => 'abu', 'label' => 'Skala Abu-abu', 'ikon' => 'abu'],
    ['kunci' => 'sorot-tautan', 'label' => 'Sorot Tautan', 'ikon' => 'tautan'],
    ['kunci' => 'renggang', 'label' => 'Teks Renggang', 'ikon' => 'renggang'],
    ['kunci' => 'henti-animasi', 'label' => 'Stop Animasi', 'ikon' => 'larang'],
    ['kunci' => 'baca-klik', 'label' => 'Baca dengan Klik', 'ikon' => 'suara'],
  ];

  // Bidang isian: biru muda di mode terang, putih tipis di mode gelap supaya tetap terangkat dari kartu.
  $bidang = 'bg-langit dark:bg-white/[0.06]';
  $tombolSkala = 'flex size-9 items-center justify-center rounded font-bold transition hover:bg-white hover:shadow-sm active:scale-90 disabled:pointer-events-none disabled:opacity-35 dark:hover:bg-white/10';
@endphp

<!-- Panel memakai Popover API: klik di luar atau Escape menutupnya tanpa skrip. -->
<button
  type="button"
  popovertarget="aksesibilitas"
  data-pemicu-aksesibilitas
  class="fixed bottom-5 left-5 z-40 rounded bg-biru-600 p-3 text-white shadow-[0_12px_28px_-10px_rgba(7,31,61,0.6)] transition hover:-translate-y-0.5 hover:bg-biru-700 active:translate-y-0 active:scale-95 [body:has(#aksesibilitas:popover-open)_&]:bg-biru-800"
  aria-label="Mode aksesibilitas"
>
  <x-situs.ikon nama="aksesibilitas" class="size-5" />
  <!-- Jumlah pengaturan yang aktif, supaya jelas kenapa tampilan situs berubah walau panel tertutup. -->
  <span data-jumlah hidden class="absolute -top-1.5 -right-1.5 flex h-5 min-w-5 items-center justify-center rounded bg-white px-1 text-[11px] font-bold text-biru-700 ring-2 ring-biru-600"></span>
</button>

<div
  id="aksesibilitas"
  popover
  role="dialog"
  aria-labelledby="judul-aksesibilitas"
  class="dialog-halus top-auto right-auto bottom-20 left-5 m-0 max-h-[calc(100dvh-6rem)] w-[min(22rem,calc(100vw-2.5rem))] origin-bottom-left overflow-y-auto rounded border border-garis bg-white p-0 text-teks shadow-[0_2px_6px_rgba(7,31,61,0.08),0_28px_56px_-16px_rgba(7,31,61,0.45)] dark:bg-malam-kartu dark:shadow-[0_28px_56px_-16px_rgba(0,0,0,0.85)]"
>
  <div class="motif-komdigi flex items-center justify-between gap-3 bg-biru-900 py-3 pr-2 pl-4 text-white">
    <h2 id="judul-aksesibilitas" class="flex items-center gap-2.5 font-bold">
      <x-situs.ikon nama="aksesibilitas" class="size-5 text-biru-300" />
      Mode Aksesibilitas
    </h2>
    <button type="button" popovertarget="aksesibilitas" popovertargetaction="hide" class="flex size-9 items-center justify-center rounded transition hover:rotate-90 hover:bg-white/10" aria-label="Tutup panel aksesibilitas">
      <x-situs.ikon nama="silang" class="size-5" />
    </button>
  </div>

  <div class="space-y-4 p-4">
    <div data-muncul style="--i: 0" class="flex items-center justify-between gap-3">
      <span id="label-ukuran-teks" class="font-semibold">Ukuran Teks</span>
      <div class="flex items-center rounded p-1 {{ $bidang }}" role="group" aria-labelledby="label-ukuran-teks">
        <button type="button" data-ubah-skala="-10" class="{{ $tombolSkala }}" aria-label="Perkecil teks">
          <span class="text-xs">A</span>&minus;
        </button>
        <output data-skala aria-live="polite" class="w-13 text-center text-sm font-bold tabular-nums">100%</output>
        <button type="button" data-ubah-skala="10" class="{{ $tombolSkala }}" aria-label="Perbesar teks">
          <span class="text-base">A</span>+
        </button>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-2">
      @foreach ($opsi as $i => $o)
        <button
          type="button"
          data-opsi="{{ $o['kunci'] }}"
          @if ($o['kunci'] === 'baca-klik') data-suara @endif
          data-muncul
          style="--i: {{ $i + 1 }}"
          aria-pressed="false"
          class="group relative flex min-h-22 flex-col items-start justify-between gap-3 rounded p-3 text-left text-sm leading-snug font-semibold transition duration-200 hover:bg-biru-100 active:scale-[0.97] dark:hover:bg-white/10 aria-pressed:bg-biru-600 aria-pressed:text-white aria-pressed:shadow-[0_10px_20px_-10px_rgba(11,78,162,0.9)] dark:aria-pressed:bg-biru-600 {{ $bidang }}"
        >
          <x-situs.ikon :nama="$o['ikon']" class="size-6 text-biru-600 transition duration-200 group-aria-pressed:text-white dark:text-biru-300" />
          {{ $o['label'] }}
          <x-situs.ikon nama="centang" class="absolute top-3 right-3 size-4 scale-50 opacity-0 transition duration-200 group-aria-pressed:scale-100 group-aria-pressed:opacity-100" />
        </button>
      @endforeach
    </div>

    <div data-suara data-muncul style="--i: 7" class="space-y-2">
      <div class="flex gap-2">
        <button type="button" data-baca-halaman class="flex flex-1 items-center justify-center gap-2 rounded bg-biru-600 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-biru-700 active:scale-[0.98]">
          <x-situs.ikon nama="putar" :isi="true" class="size-4 [[data-bicara]_&]:hidden" />
          <span class="gelombang hidden h-4 items-end gap-0.5 [[data-bicara]_&]:flex" aria-hidden="true"><span></span><span></span><span></span></span>
          Baca Halaman
        </button>
        <button type="button" data-berhenti disabled class="flex size-10.5 shrink-0 items-center justify-center rounded transition hover:bg-biru-100 active:scale-95 disabled:pointer-events-none disabled:opacity-40 dark:hover:bg-white/10 {{ $bidang }}" aria-label="Berhenti membaca" title="Berhenti">
          <x-situs.ikon nama="henti" :isi="true" class="size-4" />
        </button>
      </div>
      <p class="text-xs leading-relaxed text-teks-lembut">
        Saat &ldquo;Baca dengan Klik&rdquo; aktif, ketuk teks mana pun untuk mendengarkannya. Ketuk tautan atau tombol sekali lagi untuk membukanya.
      </p>
    </div>
  </div>

  <div class="border-t border-garis px-2 py-2">
    <button type="button" data-reset class="group flex w-full items-center justify-center gap-2 rounded px-3 py-2 text-sm font-semibold text-biru-700 transition hover:bg-langit disabled:pointer-events-none disabled:text-teks-lembut disabled:opacity-60 dark:text-biru-300 dark:hover:bg-white/5">
      <x-situs.ikon nama="ulang" class="size-4 transition duration-500 group-hover:-rotate-180" />
      Reset Pengaturan
    </button>
  </div>
</div>
