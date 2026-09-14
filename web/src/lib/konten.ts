/**
 * Konten statis situs — tidak dikelola lewat admin karena jarang berubah
 * dan terikat pada struktur organisasi, bukan pada data.
 */

/** Empat layanan utama yang tampil sebagai panel layanan beranda. */
export const layananUtama = [
  {
    ikon: 'server',
    slug: 'subdomain-hosting',
    judul: 'Subdomain & Hosting',
    ringkas: 'Subdomain .kendarikota.go.id dan hosting situs web perangkat daerah.',
  },
  {
    ikon: 'amplop',
    slug: 'email-dinas',
    judul: 'Email Dinas',
    ringkas: 'Akun email resmi @kendarikota.go.id untuk komunikasi kedinasan.',
  },
  {
    ikon: 'pena',
    slug: 'pengajuan-tte',
    judul: 'Pengajuan TTE',
    ringkas: 'Tanda tangan elektronik tersertifikasi BSrE untuk dokumen resmi.',
  },
  {
    ikon: 'telepon',
    slug: 'telpon-darurat-112',
    judul: 'Telepon Darurat 112',
    ringkas: 'Panggilan darurat bebas pulsa, aktif 24 jam di Kota Kendari.',
  },
] as const;

export const bidangDinas = [
  {
    ikon: 'gedung',
    judul: 'Sekretariat',
    isi: 'Mengelola administrasi umum, keuangan, kepegawaian, dan koordinasi internal dinas secara efektif.',
  },
  {
    ikon: 'obrolan',
    judul: 'Bidang Informasi dan Komunikasi Publik',
    isi: 'Mengelola informasi publik, hubungan media, serta strategi komunikasi pemerintah daerah secara efektif dan transparan.',
  },
  {
    ikon: 'layar',
    judul: 'Bidang Penyelenggaraan e-Government',
    isi: 'Mengembangkan dan menyelenggarakan sistem pemerintahan berbasis elektronik yang terintegrasi dan efisien.',
  },
  {
    ikon: 'perisai',
    judul: 'Bidang Teknologi Informasi, Komunikasi, dan Persandian',
    isi: 'Menyediakan infrastruktur TIK, menjaga keamanan sistem informasi, serta mengelola persandian dan komunikasi dinas.',
  },
] as const;

/** Tautan ke situs pemerintah terkait, ditampilkan sebelum footer. */
export const tautanTerkait = [
  { label: 'Pemerintah Kota Kendari', href: 'https://kendarikota.go.id' },
  { label: 'Kementerian Komunikasi dan Digital', href: 'https://komdigi.go.id' },
  { label: 'Balai Besar Sertifikasi Elektronik', href: 'https://bsre.bssn.go.id' },
  { label: 'SP4N-LAPOR!', href: 'https://www.lapor.go.id' },
  { label: 'Portal Informasi Indonesia', href: 'https://indonesia.go.id' },
];
