export type Layanan = {
  judul: string;
  ringkas: string;
  gambar: string;
  bagian: { judul: string; paragraf?: string[]; poin?: string[] };
  keunggulan?: { judul: string; isi: string }[];
  fitur?: string[];
  syarat?: string[];
  prosedur?: string[];
};

export const layanan: Record<string, Layanan> = {
  'subdomain-hosting': {
    judul: 'Subdomain & Hosting',
    ringkas:
      'Layanan pengelolaan subdomain dan hosting untuk mendukung kebutuhan situs web perangkat daerah di lingkungan Pemerintah Kota Kendari.',
    gambar: '/img/subdomain.webp',
    bagian: {
      judul: 'Tentang Layanan',
      paragraf: [
        'Dinas Komunikasi dan Informatika Kota Kendari menyediakan layanan Subdomain dan Hosting bagi perangkat daerah yang membutuhkan media publikasi berbasis web.',
        'Layanan ini mendukung keterbukaan informasi publik dan identitas digital melalui domain resmi seperti namaopd.kendarikota.go.id.',
      ],
    },
    fitur: [
      'Subdomain .kendarikota.go.id',
      'Hosting sesuai kapasitas',
      'Instalasi CMS (WordPress, Laravel)',
      'Akses panel hosting',
      'Pemantauan uptime & keamanan',
    ],
    syarat: [
      'Surat Permohonan Resmi dari Pimpinan Perangkat Daerah.',
      'Deskripsi tujuan penggunaan subdomain.',
      'Usulan nama subdomain.',
      'Kontak teknis dari OPD.',
    ],
    prosedur: [
      'OPD mengirimkan surat ke Dinas Kominfo.',
      'Tim melakukan verifikasi dan klarifikasi.',
      'Subdomain & hosting disiapkan bila disetujui.',
      'Akun & akses diberikan ke OPD.',
    ],
  },

  'email-dinas': {
    judul: 'Layanan Pembuatan Email Dinas',
    ringkas:
      'Layanan pembuatan akun email resmi dengan domain @kendarikota.go.id untuk perangkat daerah di lingkungan Pemerintah Kota Kendari.',
    gambar: '/img/email-official.svg',
    bagian: {
      judul: 'Email Resmi Pemerintah Daerah',
      paragraf: [
        'Dinas Komunikasi dan Informatika Kota Kendari menyediakan layanan pembuatan email dinas dengan domain @kendarikota.go.id. Email ini digunakan untuk komunikasi resmi, surat menyurat elektronik, dan keperluan administrasi digital perangkat daerah.',
        'Gunakan email resmi untuk meningkatkan profesionalitas dan keamanan komunikasi dinas Anda.',
      ],
    },
    keunggulan: [
      { judul: 'Keamanan Data', isi: 'Akun email dinas memiliki tingkat perlindungan dan kontrol administratif yang lebih baik.' },
      { judul: 'Identitas Resmi', isi: 'Menggunakan domain .go.id menandakan keabsahan institusi pemerintah.' },
      { judul: 'Terintegrasi', isi: 'Bisa terintegrasi dengan layanan pemerintah dan platform digital lainnya.' },
    ],
    syarat: [
      'Surat permohonan dari pimpinan OPD.',
      'Daftar nama dan jabatan yang akan dibuatkan email.',
      'Nomor HP dan email aktif sebagai kontak verifikasi.',
      'Usulan format email (misalnya nama@kendarikota.go.id).',
    ],
    prosedur: [
      'OPD mengirim permohonan ke Dinas Kominfo Kendari.',
      'Tim memverifikasi data dan kebutuhan.',
      'Akun dibuat dan disampaikan ke pemohon.',
    ],
  },

  'pengajuan-tte': {
    judul: 'Layanan Pengajuan TTE',
    ringkas:
      'Layanan pengajuan Tanda Tangan Elektronik (TTE) untuk dokumen resmi yang diakui secara hukum dan terverifikasi oleh Balai Sertifikasi Elektronik (BSrE).',
    gambar: '/img/signature.svg',
    bagian: {
      judul: 'Tanda Tangan Elektronik (TTE)',
      paragraf: [
        'Tanda Tangan Elektronik (TTE) merupakan solusi digital untuk otorisasi dokumen yang sah, aman, dan memiliki kekuatan hukum. Dinas Kominfo Kota Kendari memfasilitasi layanan ini untuk perangkat daerah guna mendukung proses birokrasi yang lebih efisien dan modern.',
        'Segera ajukan TTE untuk efisiensi dokumen dinas yang lebih cepat dan aman.',
      ],
    },
    keunggulan: [
      { judul: 'Keamanan Tinggi', isi: 'Dokumen terlindungi dengan enkripsi dan tidak dapat dipalsukan.' },
      { judul: 'Efisiensi Waktu', isi: 'Proses tanda tangan lebih cepat tanpa perlu cetak atau kirim fisik.' },
      { judul: 'Legal Nasional', isi: 'TTE diakui oleh BSrE dan sah menurut hukum Indonesia.' },
    ],
    syarat: [
      'Surat permohonan dari pimpinan perangkat daerah.',
      'Pindaian KTP dan NPWP pejabat yang mengajukan TTE.',
      'Alamat email resmi yang aktif.',
      'Dokumen atau format TTE yang akan digunakan.',
    ],
    prosedur: [
      'OPD mengirimkan permohonan ke Dinas Kominfo Kendari.',
      'Verifikasi data dan kelengkapan oleh tim TTE.',
      'Registrasi ke Balai Sertifikasi Elektronik (BSrE).',
      'Akun TTE dikirimkan ke pemohon untuk digunakan.',
    ],
  },

  'telpon-darurat-112': {
    judul: 'Layanan Telepon Darurat 112',
    ringkas:
      'Layanan panggilan bebas pulsa 112 untuk keadaan darurat di wilayah Kota Kendari, aktif 24 jam dan cepat tanggap.',
    gambar: '/img/emergency.webp',
    bagian: {
      judul: 'Apa itu Layanan 112?',
      paragraf: [
        'Layanan Telepon Darurat 112 adalah layanan panggilan cepat yang dapat digunakan oleh masyarakat Kota Kendari untuk melaporkan berbagai keadaan darurat seperti kebakaran, kecelakaan, gangguan keamanan, dan keadaan gawat darurat lainnya. Layanan ini tidak memerlukan pulsa dan tersedia selama 24 jam.',
      ],
      poin: [
        'Kebakaran rumah, gedung, atau hutan.',
        'Kecelakaan lalu lintas di jalan raya.',
        'Orang hilang atau membutuhkan evakuasi medis.',
        'Kriminalitas atau ancaman keamanan.',
        'Bencana alam seperti banjir dan tanah longsor.',
      ],
    },
    keunggulan: [
      { judul: 'Respon Cepat', isi: 'Tim operator dan instansi terkait akan segera merespon setiap laporan yang masuk.' },
      { judul: 'Layanan 24/7', isi: 'Dapat digunakan kapan saja, termasuk di malam hari dan hari libur.' },
      { judul: 'Bebas Pulsa', isi: 'Panggilan ke 112 tidak memerlukan pulsa, bahkan dari HP tanpa kartu SIM.' },
    ],
    prosedur: [
      'Tekan 112 di ponsel Anda, tanpa perlu pulsa.',
      'Sampaikan informasi darurat secara singkat dan jelas.',
      'Sebutkan lokasi kejadian secara akurat.',
      'Ikuti arahan dari petugas layanan darurat.',
    ],
  },
};
