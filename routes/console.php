<?php

use App\Support\SinkronBerita;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('berita:sinkron', function () {
    $baru = SinkronBerita::jalankan();
    $this->info($baru === null ? 'Sinkronisasi lain masih berjalan.' : "{$baru} berita baru.");
})->purpose('Ambil berita terkait Kominfo dari portal berita Kota Kendari');

Schedule::command('berita:sinkron')->everySixHours()->when(fn () => config('app.berita_wp.aktif'));
