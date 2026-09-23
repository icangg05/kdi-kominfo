<?php

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Support\SinkronBerita;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('berita:sinkron {--reset : Hapus semua berita & kategori lalu tarik ulang dari awal}', function () {
    if ($this->option('reset') && $this->confirm('Hapus SEMUA berita dan kategori berita?')) {
        // Berita dulu, lewat model: hook `deleting` menghapus file thumbnail-nya. Kalau
        // kategori dihapus duluan, FK cascade menyapu berita tanpa lewat model dan
        // thumbnail-nya jadi sampah di disk.
        Berita::cursor()->each->delete();
        KategoriBerita::query()->delete();
        $this->info('Semua berita dan kategori dihapus.');
    }

    $baru = SinkronBerita::jalankan();
    $this->info($baru === null ? 'Sinkronisasi lain masih berjalan.' : "{$baru} berita baru.");
})->purpose('Ambil berita terkait Kominfo dari portal berita Kota Kendari');

// Jam tetap 03.00, 09.00, 15.00, 21.00 (tetap berjarak 6 jam) supaya waktu sinkron
// berikutnya bisa ditampilkan di admin tanpa membaca daftar jadwal ini — routes/console.php
// hanya dimuat di konteks CLI, tidak saat request web.
Schedule::command('berita:sinkron')->cron(SinkronBerita::cron())->when(fn () => SinkronBerita::atur()['aktif']);
