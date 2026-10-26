# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Empat audiens, semuanya dikonfirmasi sebagai penentu keputusan desain:

- **Warga Kota Kendari** — mencari berita dinas, dokumen publik, galeri kegiatan, dan kontak layanan. Sebagian besar datang dari ponsel.
- **Pegawai OPD / perangkat daerah Pemkot Kendari** — datang untuk satu urusan spesifik: mengajukan subdomain & hosting, email dinas, atau TTE. Biasanya dari komputer kantor.
- **Admin Diskominfo** — operator internal yang mengelola seluruh konten publik lewat dashboard ber-autentikasi (berita, dokumen, galeri, pegawai, profil dinas, pengaturan kontak & sosial media).
- **Penilai eksternal (SPBE / keterbukaan informasi publik)** — membaca situs sebagai bukti kelengkapan dan mutu penyelenggaraan.

## Product Purpose

Situs resmi Dinas Komunikasi dan Informatika Kota Kendari: kanal informasi publik dinas sekaligus pintu masuk layanan TIK yang dinas ini sediakan untuk lingkungan Pemerintah Kota Kendari.

Sukses berarti situs ini terbaca sebagai instansi pemerintah yang berwibawa dan kredibel — setara instansi pemerintah modern, bukan template generik. Itu ukuran keberhasilan utama yang ditetapkan pemilik, dan kekurangan yang paling terasa saat ini.

## Positioning

Diskominfo adalah penyelenggara infrastruktur digital Pemkot Kendari, bukan sekadar humas. Yang tidak bisa diklaim instansi lain: dinas ini yang benar-benar memberikan subdomain `*.kendarikota.go.id`, hosting, email dinas, TTE, dan mengoperasikan Call Center 112 "Kendari Siaga". Situsnya adalah tempat layanan itu diminta, bukan hanya diumumkan.

## Operating Context

- Warga membuka dari ponsel pada jaringan seluler yang tidak selalu cepat; halaman harus tetap ringan dan cepat.
- Pegawai OPD dan admin sering membukanya di layar desktop kantor — tampilan desktop sama pentingnya, bukan turunan dari mobile.
- Konten publik hidup dari panel admin: apa pun yang dirancang harus tetap bisa diisi dan diubah oleh operator non-teknis, tanpa menyentuh kode.
- Jumlah pengunjung dihitung dan ditampilkan sebagai bagian dari akuntabilitas publik (`VisitorMiddleware`, tabel `visitors`).

## Capabilities and Constraints

Fungsi yang harus tetap ada, apa pun bentuk implementasinya:

- **Publik:** Beranda; Profil Dinas (Tentang Kami, Tupoksi, Profil Pimpinan, Profil Pegawai, Struktur Organisasi); Berita (indeks, pencarian, detail per slug, kategori); Dokumen (unduhan dengan penghitung `total_unduhan`); Galeri; empat halaman Layanan (Subdomain & Hosting, Pembuatan Email Dinas, Pengajuan TTE, Layanan Telpon Darurat 112).
- **Admin:** login dan pengelolaan penuh atas berita, kategori berita, dokumen, kategori dokumen, galeri, pegawai, jabatan, profil pimpinan, seluruh bagian profil dinas, serta pengaturan kontak dan tautan sosial media.
- **Penghitung pengunjung** publik (`VisitorMiddleware`, tabel `visitors`).

**Arsitektur (per 2026-10-26).**

- Satu aplikasi **Laravel 13** (PHP ^8.4) di atas Octane + FrankenPHP, di belakang **Caddy** (`docker/caddy/Caddyfile`, port 8000).
- **Situs publik** berupa komponen **Livewire 4** halaman penuh (`app/Livewire`, view di `resources/views/livewire`), Tailwind CSS 4 dibangun Vite. Situs Astro dan API JSON `/api/*` sudah dihapus.
- **Filament 5** sebagai panel admin di `/admin` (`app/Providers/Filament/AdminPanelProvider.php`).
- **Pengajuan layanan daring** (subdomain & hosting, email dinas, TTE): OPD mengisi formulir dan mengunggah surat permohonan di halaman layanan; admin memverifikasi di menu Layanan › Pengajuan Layanan; pemohon memantau status di `/layanan/cek-pengajuan`.
- MySQL 8.4 dan seluruh stack dijalankan lewat Docker Compose.

Batasan yang mengikat:

- **Seluruh copy publik memakai Bahasa Indonesia formal** dengan nada resmi pemerintahan. Ini satu-satunya batasan yang secara eksplisit ditetapkan pemilik sebagai tidak boleh berubah.

Secara eksplisit **tidak** mengikat (dikonfirmasi pemilik, 2026-09-03): identitas visual lama — warna `#163f6c` / `#372f4c` / `#E68425`, tipografi Sen / Public Sans, ikon Font Awesome, dan tata letak Blade yang ada — boleh dirombak total. Struktur menu juga boleh berubah. Tampilan lama adalah bukti kondisi awal, bukan acuan yang harus dipertahankan.

## Brand Commitments

- Nama resmi: **Dinas Komunikasi dan Informatika Kota Kendari** (Diskominfo Kendari). Nama aplikasi terdaftar: "Dinas Kominfo Kota Kendari".
- Nada bahasa: Indonesia formal, resmi, tanpa bahasa pemasaran.
- Logo: Lambang Kota Kendari berdampingan dengan tanda Komdigi (diminta pemilik, 2026-10-26), menggantikan logo Kominfo lama. Logo instansi pemerintah diatur di luar keputusan desain; konfirmasi ke pemilik sebelum mengubahnya lagi.

## Evidence on Hand

Konten nyata yang sudah tertulis dan dikelola lewat dashboard — jangan direkayasa ulang isinya:

- Sambutan Kepala Dinas, sejarah dinas, visi, misi, tugas, fungsi, tagline, struktur organisasi, foto kantor (`database/seeders/ProfilDinasSeeder.php`, tabel `profil_dinas`). Seeder dan migrasi selamat dari pemindahan arsitektur dan kini menjadi sumber kebenaran konten satu-satunya.
- Data pegawai dan jabatan (tabel `pegawai`, `jabatan`), profil pimpinan (`profil_pimpinan`).
- Berita, dokumen, dan galeri beserta kategorinya.
- Kontak resmi yang tersimpan sebagai pengaturan (`jenis = 'pengaturan'`): telepon `0822-4618-8268`, email `diskominfokendari@gmail.com`. Tautan Facebook, Instagram, TikTok, dan YouTube masih kosong di data awal.
- Fakta historis yang tercatat: peluncuran Call Center 112 "Kendari Siaga" (SK Wali Kota Kendari, April 2022); aplikasi Cov-Heroes, e-SPPD, LAIKA, JARI, Srikandi, E-Laika, E-Office.
- Statistik pengunjung nyata dari tabel `visitors`.

Tidak tersedia dan tidak boleh dikarang: testimoni, studi kasus, angka capaian, penghargaan, mitra, atau klaim SLA layanan.

## Product Principles

1. **Wibawa lewat kecermatan, bukan hiasan.** Kredibilitas instansi datang dari detail yang rapi dan konsisten, bukan dari efek visual yang ramai.
2. **Layanan adalah produknya.** Subdomain, email dinas, TTE, dan 112 adalah alasan pegawai OPD datang; halaman layanan harus mengantar sampai tindakan, bukan berhenti di penjelasan.
3. **Ringan di seluler, penuh di desktop.** Dua konteks pemakaian yang sama-sama nyata; tak satu pun boleh jadi sisa dari yang lain.
4. **Bisa diisi operator.** Setiap komponen baru harus punya jalur pengisian di panel admin, atau perubahan panel yang sepadan.
5. **Hanya klaim yang bisa dibuktikan.** Isi situs terbatas pada fakta yang benar-benar dimiliki dinas.

## Accessibility & Inclusion

Belum ditetapkan. Pemilik tidak memilih standar aksesibilitas tertentu (mis. WCAG AA) sebagai kewajiban saat ditanya, jadi ini dicatat sebagai keputusan terbuka, bukan sebagai persyaratan maupun sebagai penolakan. Dasar aksesibilitas tetap dikerjakan sebagai mutu bawaan.
