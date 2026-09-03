export type Tautan = { label: string; href: string; anak?: Tautan[] };

export const menuUtama: Tautan[] = [
  { label: 'Beranda', href: '/' },
  {
    label: 'Profil Dinas',
    href: '/profil-dinas/tentang-kami',
    anak: [
      { label: 'Tentang Kami', href: '/profil-dinas/tentang-kami' },
      { label: 'Tupoksi', href: '/profil-dinas/tupoksi' },
      { label: 'Profil Pimpinan', href: '/profil-dinas/profil-pimpinan' },
      { label: 'Profil Pegawai', href: '/profil-dinas/profil-pegawai' },
      { label: 'Struktur Organisasi', href: '/profil-dinas/struktur-organisasi' },
    ],
  },
  {
    label: 'Layanan',
    href: '/layanan/subdomain-hosting',
    anak: [
      { label: 'Subdomain & Hosting', href: '/layanan/subdomain-hosting' },
      { label: 'Email Dinas', href: '/layanan/email-dinas' },
      { label: 'Pengajuan TTE', href: '/layanan/pengajuan-tte' },
      { label: 'Telepon Darurat 112', href: '/layanan/telpon-darurat-112' },
    ],
  },
  { label: 'Berita', href: '/berita' },
  { label: 'Galeri', href: '/galeri' },
  { label: 'Dokumen', href: '/dokumen' },
];

export const tautanFooter: { judul: string; item: Tautan[] }[] = [
  {
    judul: 'Profil Dinas',
    item: [
      { label: 'Tentang Kami', href: '/profil-dinas/tentang-kami' },
      { label: 'Tupoksi', href: '/profil-dinas/tupoksi' },
      { label: 'Profil Pimpinan', href: '/profil-dinas/profil-pimpinan' },
      { label: 'Profil Pegawai', href: '/profil-dinas/profil-pegawai' },
      { label: 'Struktur Organisasi', href: '/profil-dinas/struktur-organisasi' },
    ],
  },
  {
    judul: 'Layanan Publik',
    item: [
      { label: 'Subdomain & Hosting', href: '/layanan/subdomain-hosting' },
      { label: 'Email Dinas', href: '/layanan/email-dinas' },
      { label: 'Pengajuan TTE', href: '/layanan/pengajuan-tte' },
      { label: 'Telepon Darurat 112', href: '/layanan/telpon-darurat-112' },
    ],
  },
  {
    judul: 'Informasi',
    item: [
      { label: 'Berita', href: '/berita' },
      { label: 'Galeri', href: '/galeri' },
      { label: 'Dokumen', href: '/dokumen' },
    ],
  },
];
