<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'Asia/makassar',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload
    |--------------------------------------------------------------------------
    |
    | Ukuran dalam KB, satuan yang dipakai maxSize() Filament. Batas PHP di
    | docker/php/upload.ini harus ikut dinaikkan kalau file_maks_kb diubah.
    |
    | Gambar JPEG/PNG/WebP dikompres ke WebP saat disimpan; yang lebih lebar
    | dari lebar_maks diperkecil. Hasil yang malah lebih besar dibuang.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Survei Kepuasan
    |--------------------------------------------------------------------------
    |
    | Tombol "Survei Kepuasan" di situs publik membuka tautan ini dalam jendela
    | di atas halaman. Matikan `aktif` untuk menyembunyikan tombolnya.
    |
    */

    'survei' => [
        'aktif' => true,
        'url' => 'https://surveidigital.spbe.go.id/embed/survey/eyJzdXJ2ZXlfaWQiOjIsInNlcnZpY2VfaWQiOjg2NSwiaG9zdCI6Imh0dHBzOi8vc3BwZC5rZW5kYXJpa290YS5nby5pZC8saHR0cDovL2xvY2FsaG9zdDo4MDA0Iiwia2V5Ijoia0NFZW9ySGgifQ==/embed/view/?jenis_layanan=SPPD%20Kota%20Kendari',
    ],

    /*
    |--------------------------------------------------------------------------
    | Berita dari Portal Berita Kota Kendari
    |--------------------------------------------------------------------------
    |
    | Berita WordPress di `url` yang memuat salah satu `kata_kunci` diambil
    | otomatis tiap 6 jam pada 03.00, 09.00, 15.00, dan 21.00 WITA (layanan
    | `scheduler` di compose.yml) dan lewat tombol
    | "Ambil berita sekarang" di admin. Kategorinya mengikuti kategori berita
    | itu di WordPress dan dibuat di lokal bila belum ada; `kategori_cadangan`
    | hanya dipakai untuk berita yang di sana tidak berkategori. Tiap sinkron
    | mengambil `maks_per_sinkron` berita terbaru lalu melewati yang `wp_id`-nya
    | sudah ada, jadi berita yang dihapus admin akan kembali saat ditarik ulang.
    | `aktif` false mematikan jadwal dan tombolnya.
    |
    | `aktif`, `kata_kunci`, dan `maks_per_sinkron` di sini cuma nilai awal:
    | yang berlaku adalah tombol "Pengaturan sinkron" di halaman Berita. `url`
    | sengaja tidak bisa diubah admin, dipakai sebagai batas host saat mengunduh
    | gambar sampul.
    |
    */

    'berita_wp' => [
        'aktif' => true,
        'url' => 'https://berita.kendarikota.go.id',
        'kata_kunci' => ['kominfo'],
        'kategori_cadangan' => 'Berita Pemkot',
        'maks_per_sinkron' => 20,
    ],

    'upload' => [
        'gambar_maks_kb' => 3 * 1024,
        'file_maks_kb' => 100 * 1024,
        'kompres_gambar' => [
            'aktif' => true,
            'lebar_maks' => 1920,
            'kualitas' => 80,
        ],
    ],

];
