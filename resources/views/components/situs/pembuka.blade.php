{{--
  Layar pembuka beranda bergaya kop surat dinas: Lambang Kota Kendari dan tanda Komdigi naik seperti bendera dikerek,
  nama instansi berjenjang, lalu garis ganda kop (tebal di atas, tipis di bawah) ditarik dari tengah.
  Hanya diputar sekali per sesi tab, saat beranda dimuat langsung (bukan lewat wire:navigate). Seluruh gerak ada di
  situs.css (bagian "Layar pembuka") dan berakhir sendiri walau skrip gagal; resources/js/situs.js hanya membolehkan
  pengunjung melewatinya. Hiasan, jadi disembunyikan dari pembaca layar: halaman di baliknya sudah utuh sejak awal.
--}}
@props(['alamat' => null])

<script>
  // 'loading' = dokumen sedang diurai pertama kali; saat wire:navigate menjalankan ulang skrip <body>, nilainya 'complete'.
  try {
    if (document.readyState === 'loading' && !sessionStorage.getItem('pembuka')) {
      sessionStorage.setItem('pembuka', '1');
      document.documentElement.dataset.pembuka = '';
    }
  } catch {}
</script>
<div class="layar-pembuka motif-komdigi bg-biru-900 text-white" aria-hidden="true" data-layar-pembuka>
  <div class="flex max-w-full flex-col items-center text-center">
    <div class="flex items-center gap-4 sm:gap-5">
      <img src="/img/lambang-kendari.webp" alt="" width="202" height="192" fetchpriority="high" class="pembuka-kerek h-20 w-auto sm:h-24" />
      <span class="pembuka-pemisah h-16 w-px bg-white/30 sm:h-20"></span>
      <img src="/img/komdigi-putih.webp" alt="" width="271" height="192" fetchpriority="high" class="pembuka-kerek pembuka-kerek-kedua h-16 w-auto sm:h-20" />
    </div>

    <p class="pembuka-naik mt-8 text-xs font-semibold tracking-[0.24em] text-biru-200 uppercase sm:text-sm">Pemerintah Kota Kendari</p>
    <p class="pembuka-nama mt-2 text-xl leading-tight font-bold tracking-[0.02em] text-balance uppercase sm:text-3xl">Dinas Komunikasi dan Informatika</p>

    <div class="mt-5 w-[min(34rem,100%)]">
      <span class="pembuka-garis block h-[3px] bg-white"></span>
      <span class="pembuka-garis pembuka-garis-tipis mt-[3px] block h-px bg-white/70"></span>
    </div>

    @if ($alamat)
      <p class="pembuka-alamat mt-4 max-w-md text-xs text-balance text-biru-200 sm:text-sm">{{ $alamat }}</p>
    @endif
  </div>
</div>
