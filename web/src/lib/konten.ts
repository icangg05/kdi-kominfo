/**
 * Konten statis situs — tidak dikelola lewat admin karena jarang berubah
 * dan terikat pada struktur organisasi, bukan pada data.
 */

/** Empat loket layanan daring; `zona` menentukan warna tanda loket di seluruh beranda. */
export const loket = [
  {
    kode: 'A',
    zona: 'a',
    slug: 'subdomain-hosting',
    judul: 'Subdomain & Hosting',
    untuk: 'Situs web perangkat daerah',
  },
  {
    kode: 'B',
    zona: 'b',
    slug: 'email-dinas',
    judul: 'Email Dinas',
    untuk: 'Akun resmi @kendarikota.go.id',
  },
  {
    kode: 'C',
    zona: 'c',
    slug: 'pengajuan-tte',
    judul: 'Pengajuan TTE',
    untuk: 'Tanda tangan elektronik BSrE',
  },
  {
    kode: '112',
    zona: 'd',
    slug: 'telpon-darurat-112',
    judul: 'Telepon Darurat',
    untuk: 'Aktif 24 jam, bebas pulsa',
  },
] as const;

export const bidangDinas = [
  {
    judul: 'Sekretariat',
    isi: 'Mengelola administrasi umum, keuangan, kepegawaian, dan koordinasi internal dinas secara efektif.',
  },
  {
    judul: 'Bidang Informasi dan Komunikasi Publik',
    isi: 'Mengelola informasi publik, hubungan media, serta strategi komunikasi pemerintah daerah secara efektif dan transparan.',
  },
  {
    judul: 'Bidang Penyelenggaraan e-Government',
    isi: 'Mengembangkan dan menyelenggarakan sistem pemerintahan berbasis elektronik yang terintegrasi dan efisien.',
  },
  {
    judul: 'Bidang Teknologi Informasi, Komunikasi, dan Persandian',
    isi: 'Menyediakan infrastruktur TIK, menjaga keamanan sistem informasi, serta mengelola persandian dan komunikasi dinas.',
  },
];
