---
version: 1
slug: "web-src-pages-index-astro"
primary_target: "web/src/pages/index.astro"
related_targets: ["web/src/components/Header.astro","web/src/components/Footer.astro"]
---

# Beranda (web/src/pages/index.astro)

Mode: Persuade (portal instansi). Cakupan: beranda dirombak; Header, Footer, font, dan radius berlaku global.
Audiens: warga (seluler), pegawai OPD (desktop kantor), penilai SPBE. Tugas: menemukan dan mengajukan layanan, membaca kabar dinas.
Batasan pemilik (2026-09-14): biru tetap dasar; hanya IBM Plex Sans; topbar tanpa tanggal, diganti jam WITA; sudut hanya `rounded` (4px); lebih banyak konten, gerak, dan warna.

## Direction contract

THESIS: Beranda sebagai aula pelayanan: satu papan display memanggil empat loket layanan daring. Menolak hero karusel biru dengan tiga kartu mengambang.

OWN-WORLD: Dinding aula #f3f5f9; papan navy #0d1526 berbingkai bezel; digit LED amber #ffb000 bertitik; loket berwarna zona (A #0b4ea2, B #0a7e6e, C #c0266d, 112 #d62d20); judul IBM Plex Sans lebar 85%; sudut 4px.

STORY: Pengunjung melihat layanan dan kabar berjalan, lalu syarat-prosedur, berita, 112, sambutan, bidang, galeri.

FIRST VIEWPORT: Topbar jam WITA; nav navy (logo resmi berteks putih) dengan tombol merah 112; papan berbingkai: strip pengunjung, nama dinas dan CTA kiri, foto Jembatan Teluk Kendari kanan, empat loket, running text di dasar. Ponsel: loket di atas foto.

FORM: Aula Loket Pelayanan, peringkat 1 daftar saya, seed 0cf9dfcb. Interaksi khas: loket memanggil saat hover/fokus.

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
