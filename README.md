username: diskominfokendari@gmail.com
password: rahasia123

# Website Dinas Kominfo Kota Kendari

Situs publik dan panel admin Dinas Komunikasi dan Informatika Kota Kendari.

## Arsitektur

Satu Caddy di depan, dua runtime di belakangnya:

```
                    ┌─────────────────────────────┐
  Browser  ──────▶  │  Caddy  (:8000)             │
                    └──────┬───────────────┬──────┘
                           │               │
   /admin, /api, /storage, │               │  sisanya
   /download, /livewire*   │               │
                           ▼               ▼
                  ┌────────────────┐  ┌──────────────┐
                  │ Laravel 13     │  │ Astro 7 SSR  │
                  │ Octane +       │  │ Node         │
                  │ FrankenPHP     │◀─┤ (ambil data  │
                  │ Filament 5     │  │  lewat /api) │
                  └───────┬────────┘  └──────────────┘
                          ▼
                     MySQL 8.4
```

Situs publik dirender Astro; Laravel hanya melayani API JSON, panel admin, dan berkas.
Astro memanggil API lewat jaringan Docker internal (`http://app:8000`), bukan lewat internet.

## Tumpukan teknologi

| Bagian | Teknologi |
|---|---|
| Bahasa | PHP 8.4 |
| Framework | Laravel 13 |
| Server aplikasi | Laravel Octane di atas FrankenPHP |
| Reverse proxy | Caddy 2 |
| Panel admin | Filament 5 |
| Situs publik | Astro 7 (SSR, adapter Node) |
| CSS | Tailwind CSS 4.3 |
| Basis data | MySQL 8.4 |

**Catatan Livewire.** Livewire tidak dipakai di situs publik, tetapi tetap terpasang
sebagai dependensi wajib Filament 5 (`filament/support` membutuhkan `livewire/livewire ^4.1`).
Cakupannya hanya `/admin`.

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

Kode di-bind mount dan Astro berjalan dengan HMR, jadi perubahan langsung terlihat.

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
  Http/Controllers/
    Api/SiteController.php   Satu-satunya sumber data untuk Astro
  Models/
web/                 Situs publik Astro
  src/pages/         Rute halaman
  src/components/    Komponen tampilan
  src/lib/           Klien API, navigasi, konten statis
  src/styles/        Token tema Komdigi
docker/
  caddy/Caddyfile    Aturan pembagian rute
  php/Dockerfile     Image FrankenPHP + Octane
  node/Dockerfile    Image Astro (multi-stage)
```

## Mengelola konten

Semua lewat `/admin`:

- **Konten** — Berita, Kategori Berita, Galeri, Dokumen, Kategori Dokumen
- **Profil Dinas** — Isi Halaman (sambutan, sejarah, visi, misi, tugas, fungsi,
  struktur organisasi, foto kantor), Profil Pimpinan, Pegawai, Jabatan
- **Pengaturan** — nomor telepon, email, dan tautan media sosial yang tampil
  di bilah atas dan footer situs publik

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
