username: diskominfokendari@gmail.com
password: rahasia123

# Website Dinas Kominfo Kota Kendari

Situs publik dan panel admin Dinas Komunikasi dan Informatika Kota Kendari.

## Arsitektur

Satu Caddy di depan, satu aplikasi Laravel di belakangnya:

```
                    ┌─────────────────────────────┐
  Browser  ──────▶  │  Caddy  (:8000)             │
                    └──────────────┬──────────────┘
                                   ▼
                  ┌─────────────────────────────────┐
                  │ Laravel 13 + Octane/FrankenPHP  │
                  │  • situs publik: Livewire 4     │
                  │  • panel admin: Filament 5      │
                  └───────────────┬─────────────────┘
                                  ▼
                              MySQL 8.4
```

Situs publik adalah komponen Livewire halaman penuh (`app/Livewire`); tiap halaman
mengambil data langsung dari model Eloquent, tanpa API perantara. Tautan antarhalaman
memakai `wire:navigate`, jadi perpindahan halaman seperti SPA (tanpa muat ulang);
`resources/js/situs.js` memasang ulang perilaku per halaman di event
`livewire:navigated` dan memulihkan tema/aksesibilitas saat `<body>` ditukar. Daftar berita,
dokumen, galeri, dan pegawai memakai Livewire untuk pencarian langsung, filter
kategori, dan paginasi tanpa memuat ulang halaman, dengan parameter URL yang sama
seperti sebelumnya (`?search=`, `?kategori=`, `?page=`).

## Tumpukan teknologi

| Bagian | Teknologi |
|---|---|
| Bahasa | PHP 8.4 |
| Framework | Laravel 13 |
| Server aplikasi | Laravel Octane di atas FrankenPHP |
| Reverse proxy | Caddy 2 |
| Situs publik | Livewire 4 (Alpine bawaan Livewire) |
| Panel admin | Filament 5 |
| CSS / skrip | Tailwind CSS 4.3 dan Vite (`resources/css/situs.css`, `resources/js/situs.js`) |
| Basis data | MySQL 8.4 |

## Menjalankan

### Produksi

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
```

Situs di `http://localhost:8000`, panel admin di `http://localhost:8000/admin`.

Seeder membuat satu akun admin dari `ADMIN_EMAIL` dan `ADMIN_PASSWORD` di `.env`.
Belum ada pembagian role — setiap akun yang bisa masuk punya akses penuh yang sama,
jadi menambah akun kedua tidak membatasi apa pun. **Ganti `ADMIN_PASSWORD` sebelum
naik ke server sungguhan.**

Kode dan aset Vite dibangun ke dalam image (`docker/php/Dockerfile`, tahap `aset`), jadi
setelah mengubah kode jalankan `docker compose up -d --build`. Untuk mengembangkan
tampilan tanpa build ulang image, `npm install && npm run build` membangun aset ke
`public/build`.

Setelah memperbarui dari versi Astro: `docker compose up -d --build --remove-orphans`
(menghapus kontainer `kdi-web` lama), lalu `docker compose exec app php artisan migrate`.

### Pengujian

```bash
docker compose exec app php artisan test
```

Pengujian memakai SQLite in-memory, dipaksa di `tests/bootstrap.php`. Ini tidak
bisa hanya diatur lewat `phpunit.xml`: compose menyuntikkan `DB_*` sebagai variabel
lingkungan asli yang mendarat di `$_SERVER`, dan Laravel membaca `$_SERVER` lebih
dulu daripada `$_ENV` — sehingga `<env force="true">` kalah dan pengujian akan
menghantam basis data pengembangan.

## Struktur

```
app/
  Filament/          Resource, halaman, dan widget panel admin
  Livewire/          Halaman situs publik (Beranda, Berita, Dokumen, Galeri, Profil, Layanan)
  Models/
  Support/Konten.php Menu, layanan, dan konten statis situs
  Support/Situs.php  Pembantu tampilan: URL berkas, tanggal, angka
resources/
  views/livewire/            View halaman situs publik
  views/components/situs/    Header, footer, logo, ikon, kartu, isian formulir
  views/components/layouts/  Layout situs publik
  css/situs.css              Token tema Komdigi (Tailwind 4)
  js/situs.js                Tema, jam WITA, menu, dialog, panel aksesibilitas
public/img, public/fonts     Aset statis situs dan admin
docker/
  caddy/Caddyfile    Aturan proxy
  php/Dockerfile     Image FrankenPHP + Octane, dengan tahap build Vite
```

## Mengelola konten

Semua lewat `/admin`:

- **Layanan** — Pengajuan Layanan (lihat di bawah)
- **Konten** — Berita, Kategori Berita, Galeri, Dokumen, Kategori Dokumen
- **Profil Dinas** — Isi Halaman (sambutan, sejarah, visi, misi, tugas, fungsi,
  struktur organisasi, foto kantor), Profil Pimpinan, Pegawai, Jabatan
- **Pengaturan** — nomor telepon, email, dan tautan media sosial yang tampil
  di bilah atas dan footer situs publik

### Pengajuan layanan daring

Halaman Subdomain & Hosting, Email Dinas, dan Pengajuan TTE punya formulir pengajuan
dengan isian per layanan (disimpan di kolom JSON `rincian`, daftarnya di
`PengajuanLayanan::JENIS`) dan surat permohonan (PDF/Word/JPG/PNG, maks. 10 MB):

| Layanan | Isian |
| --- | --- |
| TTE | nama, NIK, NIP, instansi, jabatan, tempat/tanggal lahir, nomor HP, KTP (JPG/PNG/PDF), surat |
| Email dinas | nama, NIP, tempat/tanggal lahir, nomor HP, instansi, surat |
| Subdomain | nama instansi/OPD, nama subdomain, jenis layanan (publik/administrasi pemerintahan), IP tujuan (opsional), lokasi hosting/server, deskripsi fungsi, nama dan nomor HP penanggung jawab, surat |

Pemohon mendapat nomor pengajuan, mis. `TTE-20261026-K7Q2`. Di `/layanan/cek-pengajuan`
pemohon cukup memasukkan nomor HP (disimpan seragam `08…`, jadi `+62 812-…` dan `0812 …`
dianggap sama) untuk melihat semua pengajuan dengan nomor itu, lalu memilih satu untuk
melihat tahapan dan catatan petugas. Halaman itu tidak menampilkan NIK, NIP, TTL, atau berkas.

Admin memeriksa di **Layanan › Pengajuan Layanan** (angka di menu = yang menunggu),
membuka surat langsung di peramban, menghubungi pemohon lewat tautan WhatsApp, lalu
**Proses**, **Setujui**, atau **Tolak**. Catatan admin (wajib saat menolak) terlihat
oleh pemohon di halaman cek status.

Surat dan KTP disimpan di disk privat `local` (`storage/app/private/pengajuan`), bukan di
`public`, jadi hanya bisa dibuka admin yang login lewat
`/admin/pengajuan-layanan/{id}/surat` (atau `/ktp`). Kiriman dibatasi 5 per IP per jam, dan
kiriman yang lebih cepat dari 3 detik sejak formulir dimuat dianggap bot (bukan isian perangkap tersembunyi, yang ikut terisi autofill peramban). Foto KTP dan pindaian surat dikompres ke WebP. Petunjuk isi surat per layanan diatur di
`PengajuanLayanan::JENIS`.

### Berita dari portal kota

Selain diinput manual, berita yang menyebut Kominfo diambil dari WordPress
[berita.kendarikota.go.id](https://berita.kendarikota.go.id): otomatis tiap 6 jam
pada **03.00, 09.00, 15.00, dan 21.00 WITA** oleh layanan `scheduler` di
`compose.yml`, atau lewat tombol **Ambil berita sekarang** di halaman Berita.

Jamnya sengaja tetap (`SinkronBerita::JAM`), bukan `everySixHours()`, supaya
halaman admin bisa menampilkan waktu sinkron berikutnya tanpa membaca daftar
`Schedule` — `routes/console.php` hanya dimuat di konteks CLI, tidak saat request
web. Tombol **Ambil berita sekarang** dihitung sebagai sinkron manual dan tidak
menggeser catatan "sinkron otomatis terakhir".

Kata kunci pencarian, batas per sinkron, dan saklar aktif/nonaktif diatur admin
lewat tombol **Pengaturan sinkron** di halaman yang sama; nilai di
`config/app.php` (`berita_wp`) cuma dipakai sebelum admin pernah menyimpan.
Alamat portalnya sengaja tetap di config karena dipakai membatasi host saat
mengunduh gambar sampul.

Kategori tiap berita mengikuti kategorinya di portal dan dibuat otomatis di
lokal kalau belum ada — karena itu `kategori_berita` tidak di-seed. Berita yang
di sana tidak berkategori masuk ke `kategori_cadangan`.

Pencarian dibatasi ke judul (`search_columns=post_title`) supaya hasilnya sama
persis dengan halaman `/?s=kata` di portal; tanpa itu REST API ikut mencari ke
isi berita dan menarik berita yang judulnya tidak menyebut kata kuncinya.

Tiap sinkron mengambil sejumlah berita terbaru lalu melewati yang `wp_id`-nya
sudah tersimpan. Jadi berita yang dihapus di admin akan muncul lagi saat ditarik
ulang, dan berita yang sudah ada tidak pernah terambil dua kali. Dari terminal:

```bash
docker compose exec app php artisan berita:sinkron

# Hapus semua berita + kategori (beserta file thumbnail-nya) lalu tarik dari nol
docker compose exec app php artisan berita:sinkron --reset
```
