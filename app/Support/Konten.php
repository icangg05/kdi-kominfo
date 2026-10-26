<?php

namespace App\Support;

/**
 * Konten statis situs publik — tidak dikelola lewat admin karena jarang berubah
 * dan terikat pada struktur organisasi, bukan pada data.
 */
final class Konten
{
  public const MENU_UTAMA = [
    ['label' => 'Beranda', 'href' => '/'],
    [
      'label' => 'Profil Dinas',
      'href' => '/profil-dinas/tentang-kami',
      'anak' => [
        ['label' => 'Tentang Kami', 'href' => '/profil-dinas/tentang-kami'],
        ['label' => 'Tupoksi', 'href' => '/profil-dinas/tupoksi'],
        ['label' => 'Profil Pimpinan', 'href' => '/profil-dinas/profil-pimpinan'],
        ['label' => 'Profil Pegawai', 'href' => '/profil-dinas/profil-pegawai'],
        ['label' => 'Struktur Organisasi', 'href' => '/profil-dinas/struktur-organisasi'],
      ],
    ],
    [
      'label' => 'Layanan',
      'href' => '/layanan/subdomain-hosting',
      'anak' => [
        ['label' => 'Subdomain & Hosting', 'href' => '/layanan/subdomain-hosting'],
        ['label' => 'Email Dinas', 'href' => '/layanan/email-dinas'],
        ['label' => 'Pengajuan TTE', 'href' => '/layanan/pengajuan-tte'],
        ['label' => 'Telepon Darurat 112', 'href' => '/layanan/telpon-darurat-112'],
        ['label' => 'Cek Status Pengajuan', 'href' => '/layanan/cek-pengajuan'],
      ],
    ],
    ['label' => 'Berita', 'href' => '/berita'],
    [
      'label' => 'Galeri',
      'href' => '/galeri',
      'anak' => [
        ['label' => 'Foto', 'href' => '/galeri'],
        ['label' => 'Video', 'href' => '/galeri/video'],
      ],
    ],
    ['label' => 'Dokumen', 'href' => '/dokumen'],
  ];

  public const TAUTAN_FOOTER = [
    [
      'judul' => 'Profil Dinas',
      'item' => [
        ['label' => 'Tentang Kami', 'href' => '/profil-dinas/tentang-kami'],
        ['label' => 'Tupoksi', 'href' => '/profil-dinas/tupoksi'],
        ['label' => 'Profil Pimpinan', 'href' => '/profil-dinas/profil-pimpinan'],
        ['label' => 'Profil Pegawai', 'href' => '/profil-dinas/profil-pegawai'],
        ['label' => 'Struktur Organisasi', 'href' => '/profil-dinas/struktur-organisasi'],
      ],
    ],
    [
      'judul' => 'Layanan Publik',
      'item' => [
        ['label' => 'Subdomain & Hosting', 'href' => '/layanan/subdomain-hosting'],
        ['label' => 'Email Dinas', 'href' => '/layanan/email-dinas'],
        ['label' => 'Pengajuan TTE', 'href' => '/layanan/pengajuan-tte'],
        ['label' => 'Telepon Darurat 112', 'href' => '/layanan/telpon-darurat-112'],
        ['label' => 'Cek Status Pengajuan', 'href' => '/layanan/cek-pengajuan'],
      ],
    ],
    [
      'judul' => 'Informasi',
      'item' => [
        ['label' => 'Berita', 'href' => '/berita'],
        ['label' => 'Galeri Foto', 'href' => '/galeri'],
        ['label' => 'Galeri Video', 'href' => '/galeri/video'],
        ['label' => 'Dokumen', 'href' => '/dokumen'],
      ],
    ],
  ];

  /** Empat layanan utama yang tampil sebagai panel layanan beranda. */
  public const LAYANAN_UTAMA = [
    ['ikon' => 'server', 'slug' => 'subdomain-hosting', 'judul' => 'Subdomain & Hosting', 'ringkas' => 'Subdomain .kendarikota.go.id dan hosting situs web perangkat daerah.'],
    ['ikon' => 'amplop', 'slug' => 'email-dinas', 'judul' => 'Email Dinas', 'ringkas' => 'Akun email resmi @kendarikota.go.id untuk komunikasi kedinasan.'],
    ['ikon' => 'pena', 'slug' => 'pengajuan-tte', 'judul' => 'Pengajuan TTE', 'ringkas' => 'Tanda tangan elektronik tersertifikasi BSrE untuk dokumen resmi.'],
    ['ikon' => 'telepon', 'slug' => 'telpon-darurat-112', 'judul' => 'Telepon Darurat 112', 'ringkas' => 'Panggilan darurat bebas pulsa, aktif 24 jam di Kota Kendari.'],
  ];

  public const BIDANG_DINAS = [
    ['ikon' => 'gedung', 'judul' => 'Sekretariat', 'isi' => 'Mengelola administrasi umum, keuangan, kepegawaian, dan koordinasi internal dinas secara efektif.'],
    ['ikon' => 'obrolan', 'judul' => 'Bidang Informasi dan Komunikasi Publik', 'isi' => 'Mengelola informasi publik, hubungan media, serta strategi komunikasi pemerintah daerah secara efektif dan transparan.'],
    ['ikon' => 'layar', 'judul' => 'Bidang Penyelenggaraan e-Government', 'isi' => 'Mengembangkan dan menyelenggarakan sistem pemerintahan berbasis elektronik yang terintegrasi dan efisien.'],
    ['ikon' => 'perisai', 'judul' => 'Bidang Teknologi Informasi, Komunikasi, dan Persandian', 'isi' => 'Menyediakan infrastruktur TIK, menjaga keamanan sistem informasi, serta mengelola persandian dan komunikasi dinas.'],
  ];

  /** Tautan ke situs pemerintah terkait, ditampilkan sebelum footer beranda. */
  public const TAUTAN_TERKAIT = [
    ['label' => 'Pemerintah Kota Kendari', 'href' => 'https://kendarikota.go.id'],
    ['label' => 'Kementerian Komunikasi dan Digital', 'href' => 'https://komdigi.go.id'],
    ['label' => 'Balai Besar Sertifikasi Elektronik', 'href' => 'https://bsre.bssn.go.id'],
    ['label' => 'SP4N-LAPOR!', 'href' => 'https://www.lapor.go.id'],
    ['label' => 'Portal Informasi Indonesia', 'href' => 'https://indonesia.go.id'],
  ];

  /**
   * Isi halaman /layanan/{slug}. Layanan yang slug-nya ada di PengajuanLayanan::JENIS
   * juga menampilkan formulir pengajuan daring.
   */
  public const LAYANAN = [
    'subdomain-hosting' => [
      'judul' => 'Subdomain & Hosting',
      'ringkas' => 'Layanan pengelolaan subdomain dan hosting untuk mendukung kebutuhan situs web perangkat daerah di lingkungan Pemerintah Kota Kendari.',
      'gambar' => '/img/subdomain.webp',
      'bagian' => [
        'judul' => 'Tentang Layanan',
        'paragraf' => [
          'Dinas Komunikasi dan Informatika Kota Kendari menyediakan layanan Subdomain dan Hosting bagi perangkat daerah yang membutuhkan media publikasi berbasis web.',
          'Layanan ini mendukung keterbukaan informasi publik dan identitas digital melalui domain resmi seperti namaopd.kendarikota.go.id.',
        ],
      ],
      'fitur' => [
        'Subdomain .kendarikota.go.id',
        'Hosting sesuai kapasitas',
        'Instalasi CMS (WordPress, Laravel)',
        'Akses panel hosting',
        'Pemantauan uptime & keamanan',
      ],
      'syarat' => [
        'Surat permohonan resmi dari pimpinan perangkat daerah.',
        'Deskripsi tujuan penggunaan subdomain.',
        'Usulan nama subdomain.',
        'Kontak teknis dari OPD.',
      ],
      'prosedur' => [
        'OPD mengisi formulir dan mengunggah surat permohonan di halaman ini.',
        'Tim melakukan verifikasi dan klarifikasi.',
        'Subdomain & hosting disiapkan bila disetujui.',
        'Akun & akses diberikan ke OPD.',
      ],
    ],

    'email-dinas' => [
      'judul' => 'Layanan Pembuatan Email Dinas',
      'ringkas' => 'Layanan pembuatan akun email resmi dengan domain @kendarikota.go.id untuk perangkat daerah di lingkungan Pemerintah Kota Kendari.',
      'gambar' => '/img/email-official.svg',
      'bagian' => [
        'judul' => 'Email Resmi Pemerintah Daerah',
        'paragraf' => [
          'Dinas Komunikasi dan Informatika Kota Kendari menyediakan layanan pembuatan email dinas dengan domain @kendarikota.go.id. Email ini digunakan untuk komunikasi resmi, surat menyurat elektronik, dan keperluan administrasi digital perangkat daerah.',
          'Gunakan email resmi untuk meningkatkan profesionalitas dan keamanan komunikasi dinas Anda.',
        ],
      ],
      'keunggulan' => [
        ['judul' => 'Keamanan Data', 'isi' => 'Akun email dinas memiliki tingkat perlindungan dan kontrol administratif yang lebih baik.'],
        ['judul' => 'Identitas Resmi', 'isi' => 'Menggunakan domain .go.id menandakan keabsahan institusi pemerintah.'],
        ['judul' => 'Terintegrasi', 'isi' => 'Bisa terintegrasi dengan layanan pemerintah dan platform digital lainnya.'],
      ],
      'syarat' => [
        'Surat permohonan dari pimpinan OPD.',
        'Daftar nama dan jabatan yang akan dibuatkan email.',
        'Nomor WhatsApp aktif sebagai kontak verifikasi.',
        'Usulan format email (misalnya nama@kendarikota.go.id).',
      ],
      'prosedur' => [
        'OPD mengisi formulir dan mengunggah surat permohonan di halaman ini.',
        'Tim memverifikasi data dan kebutuhan.',
        'Akun dibuat dan disampaikan ke pemohon.',
      ],
    ],

    'pengajuan-tte' => [
      'judul' => 'Layanan Pengajuan TTE',
      'ringkas' => 'Layanan pengajuan Tanda Tangan Elektronik (TTE) untuk dokumen resmi yang diakui secara hukum dan terverifikasi oleh Balai Sertifikasi Elektronik (BSrE).',
      'gambar' => '/img/signature.svg',
      'bagian' => [
        'judul' => 'Tanda Tangan Elektronik (TTE)',
        'paragraf' => [
          'Tanda Tangan Elektronik (TTE) merupakan solusi digital untuk otorisasi dokumen yang sah, aman, dan memiliki kekuatan hukum. Dinas Kominfo Kota Kendari memfasilitasi layanan ini untuk perangkat daerah guna mendukung proses birokrasi yang lebih efisien dan modern.',
          'Segera ajukan TTE untuk efisiensi dokumen dinas yang lebih cepat dan aman.',
        ],
      ],
      'keunggulan' => [
        ['judul' => 'Keamanan Tinggi', 'isi' => 'Dokumen terlindungi dengan enkripsi dan tidak dapat dipalsukan.'],
        ['judul' => 'Efisiensi Waktu', 'isi' => 'Proses tanda tangan lebih cepat tanpa perlu cetak atau kirim fisik.'],
        ['judul' => 'Legal Nasional', 'isi' => 'TTE diakui oleh BSrE dan sah menurut hukum Indonesia.'],
      ],
      'syarat' => [
        'Surat permohonan dari pimpinan perangkat daerah.',
        'Nama, NIP, dan jabatan pejabat yang mengajukan TTE dicantumkan dalam surat.',
        'Alamat email resmi yang aktif.',
        'Dokumen atau format TTE yang akan digunakan.',
      ],
      'prosedur' => [
        'OPD mengisi formulir dan mengunggah surat permohonan di halaman ini.',
        'Verifikasi data dan kelengkapan oleh tim TTE.',
        'Registrasi ke Balai Sertifikasi Elektronik (BSrE).',
        'Akun TTE dikirimkan ke pemohon untuk digunakan.',
      ],
    ],

    'telpon-darurat-112' => [
      'judul' => 'Layanan Telepon Darurat 112',
      'ringkas' => 'Layanan panggilan bebas pulsa 112 untuk keadaan darurat di wilayah Kota Kendari, aktif 24 jam dan cepat tanggap.',
      'gambar' => '/img/emergency.webp',
      'bagian' => [
        'judul' => 'Apa itu Layanan 112?',
        'paragraf' => [
          'Layanan Telepon Darurat 112 adalah layanan panggilan cepat yang dapat digunakan oleh masyarakat Kota Kendari untuk melaporkan berbagai keadaan darurat seperti kebakaran, kecelakaan, gangguan keamanan, dan keadaan gawat darurat lainnya. Layanan ini tidak memerlukan pulsa dan tersedia selama 24 jam.',
        ],
        'poin' => [
          'Kebakaran rumah, gedung, atau hutan.',
          'Kecelakaan lalu lintas di jalan raya.',
          'Orang hilang atau membutuhkan evakuasi medis.',
          'Kriminalitas atau ancaman keamanan.',
          'Bencana alam seperti banjir dan tanah longsor.',
        ],
      ],
      'keunggulan' => [
        ['judul' => 'Respon Cepat', 'isi' => 'Tim operator dan instansi terkait akan segera merespon setiap laporan yang masuk.'],
        ['judul' => 'Layanan 24/7', 'isi' => 'Dapat digunakan kapan saja, termasuk di malam hari dan hari libur.'],
        ['judul' => 'Bebas Pulsa', 'isi' => 'Panggilan ke 112 tidak memerlukan pulsa, bahkan dari HP tanpa kartu SIM.'],
      ],
      'prosedur' => [
        'Tekan 112 di ponsel Anda, tanpa perlu pulsa.',
        'Sampaikan informasi darurat secara singkat dan jelas.',
        'Sebutkan lokasi kejadian secara akurat.',
        'Ikuti arahan dari petugas layanan darurat.',
      ],
    ],
  ];
}
