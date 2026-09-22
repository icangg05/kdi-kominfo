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
[berita.kendarikota.go.id](https://berita.kendarikota.go.id) ke kategori
"Berita Pemkot": otomatis setiap 6 jam oleh layanan `scheduler` di
`compose.yml`, atau lewat tombol **Ambil berita sekarang** di halaman Berita.
Kata kunci, kategori, dan batas per sinkron diatur di `config/app.php`
(`berita_wp`). Sinkron hanya maju ke berita yang lebih baru, jadi berita hasil
sinkron yang dihapus di admin tidak muncul lagi. Dari terminal:

```bash
docker compose exec app php artisan berita:sinkron
```
