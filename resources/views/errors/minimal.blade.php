{{--
  Halaman galat polos untuk panel admin, unduhan, berkas, dan semua galat 5xx.
  Menimpa layout `errors::minimal` bawaan, yang di-extend view per kode milik framework
  (401, 403, 419, 429, 500, 503, ...); kode lain masuk lewat 4xx/5xx.blade.php.
  404 di situs publik memakai layout situs lengkap (errors/404.blade.php).
  Section dari view bawaan diabaikan: judul dan pesan dipilih di sini per kode.
  Berdiri sendiri tanpa build CSS (tetap tampil walau aset Vite atau basis data bermasalah);
  warna mengikuti token di resources/css/situs.css.
--}}
@php
  $kode = $exception->getStatusCode();

  [$judul, $pesan] = match ($kode) {
    401 => ['Perlu masuk', 'Silakan masuk terlebih dahulu untuk membuka halaman ini.'],
    403 => ['Akses ditolak', 'Anda tidak memiliki izin untuk membuka halaman ini.'],
    404 => ['Halaman tidak ditemukan', 'Halaman atau berkas yang Anda tuju mungkin sudah dipindahkan, dihapus, atau tidak pernah ada.'],
    405 => ['Metode tidak diizinkan', 'Permintaan ini tidak didukung oleh alamat yang Anda tuju.'],
    413 => ['Berkas terlalu besar', 'Ukuran data yang dikirim melebihi batas yang diizinkan server. Perkecil berkas lalu coba lagi.'],
    419 => ['Sesi telah berakhir', 'Halaman kedaluwarsa karena terlalu lama tidak aktif. Muat ulang halaman lalu coba lagi.'],
    429 => ['Terlalu banyak permintaan', 'Anda mengirim terlalu banyak permintaan dalam waktu singkat. Tunggu sebentar lalu coba lagi.'],
    503 => ['Sedang dalam pemeliharaan', 'Layanan sedang dirawat dan akan segera kembali. Silakan coba lagi beberapa saat lagi.'],
    default => $kode >= 500
      ? ['Terjadi kesalahan pada server', 'Ada gangguan di sisi kami. Silakan coba lagi beberapa saat lagi.']
      : ['Permintaan tidak dapat diproses', 'Periksa kembali alamat yang Anda tuju lalu coba lagi.'],
  };

  // Kode yang bisa pulih dengan memuat ulang mendapat tombol "Coba lagi". Galat dari kiriman
  // formulir diulang dari halaman asalnya, karena alamat POST tidak bisa dibuka dengan GET.
  $cobaLagi = $kode >= 500 || in_array($kode, [419, 429]);
  $ulang = request()->isMethod('GET') ? request()->fullUrl() : url()->previous();
  // Di panel admin, jalan pulang yang berguna adalah dasbor, bukan beranda situs.
  [$pulang, $labelPulang] = request()->is('admin*')
    ? [url('/admin'), 'Kembali ke dasbor']
    : [url('/'), 'Kembali ke beranda'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <meta name="theme-color" content="#0b2f5c">
  <title>{{ $judul }} | Diskominfo Kota Kendari</title>
  <link rel="icon" href="/favicon.ico">
  {{-- Kunci `theme` sama dengan situs dan panel Filament, jadi mode gelap ikut terbawa. --}}
  <script>
    try {
      const t = localStorage.getItem('theme');
      if (t === 'dark' || (t === 'system' && matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.dataset.tema = 'gelap';
      }
    } catch {}
  </script>
  <style>
    @font-face {
      font-family: "IBM Plex Sans";
      font-weight: 400 700;
      font-display: swap;
      src: url("/fonts/ibm-plex-sans-latin.woff2") format("woff2");
    }

    :root {
      --latar: #fff;
      --judul: #0b2f5c;
      --kode: #0b4ea2;
      --teks: #4a5568;
      --garis: #dbe3ef;
      --tombol-sekunder: #0d478e;
      --sorot: #f2f7fd;
    }

    :root[data-tema="gelap"] {
      color-scheme: dark;
      --latar: #05152b;
      --judul: #fff;
      --kode: #4f95dc;
      --teks: #9fb6d4;
      --garis: #1d3558;
      --tombol-sekunder: #c0d8f4;
      --sorot: rgb(255 255 255 / 0.05);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: var(--latar);
      color: var(--teks);
      font-family: "IBM Plex Sans", ui-sans-serif, system-ui, sans-serif;
      -webkit-font-smoothing: antialiased;
    }

    header { background: #0b2f5c; }

    .wadah {
      width: 100%;
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 16px;
    }

    header .wadah { display: flex; align-items: center; height: 72px; }
    .logo { display: flex; align-items: center; gap: 10px; color: #fff; text-decoration: none; }
    .logo img { display: block; height: 40px; width: auto; }
    .logo img + .pemisah + img { height: 32px; }
    .pemisah { width: 1px; height: 32px; background: rgb(255 255 255 / 0.3); }
    .nama { margin-left: 4px; line-height: 1.2; }
    .nama b { display: block; font-size: 15px; }
    .nama small { display: block; margin-top: 2px; font-size: 12px; color: #c0d8f4; }

    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding-block: 80px;
      text-align: center;
    }

    .kode { margin: 0; font-size: 3.75rem; line-height: 1; font-weight: 800; color: var(--kode); }
    h1 { margin: 16px 0 0; font-size: 1.5rem; font-weight: 700; color: var(--judul); }
    main p:not(.kode) { margin: 12px 0 0; max-width: 28rem; line-height: 1.6; }

    .aksi { margin-top: 32px; display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }

    .tombol {
      display: inline-block;
      border-radius: 4px;
      border: 1px solid var(--garis);
      padding: 12px 20px;
      font-size: 0.875rem;
      font-weight: 600;
      text-decoration: none;
      color: var(--tombol-sekunder);
      transition: background-color 0.15s;
    }

    .tombol:hover { background: var(--sorot); }
    .tombol.utama { border-color: #0b4ea2; background: #0b4ea2; color: #fff; }
    .tombol.utama:hover { background: #0d478e; }

    :focus-visible { outline: 2px solid #0b4ea2; outline-offset: 2px; box-shadow: 0 0 0 2px #fff; }

    @media (min-width: 1024px) {
      .wadah { padding: 0 32px; }
      .logo img { height: 48px; }
      .logo img + .pemisah + img, .pemisah { height: 40px; }
    }
  </style>
</head>
<body>
  <header>
    <div class="wadah">
      <a href="{{ url('/') }}" class="logo">
        {{-- Bila gambar gagal dimuat, sembunyikan daripada menampilkan ikon rusak; nama dinas tetap terbaca. --}}
        <img src="/img/lambang-kendari.webp" alt="Lambang Kota Kendari" width="202" height="192" onerror="this.remove()">
        <span class="pemisah" aria-hidden="true"></span>
        <img src="/img/komdigi-putih.webp" alt="Komdigi" width="271" height="192" onerror="this.remove()">
        <span class="nama"><b>Diskominfo</b><small>Kota Kendari</small></span>
      </a>
    </div>
  </header>

  <main class="wadah">
    <p class="kode">{{ $kode }}</p>
    <h1>{{ $judul }}</h1>
    <p>{{ $pesan }}</p>
    <div class="aksi">
      @if ($cobaLagi)
        <a href="{{ $ulang }}" class="tombol utama">Coba lagi</a>
        @if ($ulang !== $pulang)
          <a href="{{ $pulang }}" class="tombol">{{ $labelPulang }}</a>
        @endif
      @else
        <a href="{{ $pulang }}" class="tombol utama">{{ $labelPulang }}</a>
      @endif
    </div>
  </main>
</body>
</html>
