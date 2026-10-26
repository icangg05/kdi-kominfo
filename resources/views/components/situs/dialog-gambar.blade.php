{{--
  Satu dialog untuk semua gambar di dalam [data-perbesar]; klik di luar gambar untuk menutup.
  Gambar ber-data-judul/data-tanggal (galeri) ikut menampilkan keterangan. Figure dibuat tabel supaya
  keterangan (table-caption) selebar gambar, bukan melebar mengikuti panjang judul.
  Ditaruh di luar komponen Livewire (atau dengan wire:ignore) supaya tidak tersentuh saat daftar dirender ulang.
--}}
<dialog id="dialog-gambar" aria-label="Gambar diperbesar" class="dialog-halus m-auto max-h-none max-w-none overflow-visible bg-transparent p-0 [--pekat:0.92]" wire:ignore>
  <figure class="table">
    <img alt="" class="max-h-[calc(100dvh-2rem)] max-w-[calc(100vw-2rem)] rounded object-contain" />
    <figcaption class="table-caption caption-bottom pt-3 text-white" hidden>
      <p class="line-clamp-2 font-semibold leading-snug" data-judul></p>
      <p class="mt-1 text-sm text-biru-200" data-tanggal></p>
    </figcaption>
  </figure>
  <form method="dialog">
    <button class="absolute top-2 right-2 flex size-10 items-center justify-center rounded bg-biru-950/70 text-white transition hover:bg-biru-950" aria-label="Tutup gambar">
      <x-situs.ikon nama="silang" class="size-5" />
    </button>
  </form>
</dialog>
