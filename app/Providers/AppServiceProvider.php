<?php

namespace App\Providers;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  public function boot(): void
  {
    Carbon::setLocale(config('app.locale'));

    // Sapu sisa upload PHP tiap ada aktivitas Livewire/Filament (buka form,
    // klik, upload). Digantung di RequestHandled, bukan langsung di boot(),
    // karena di bawah Octane boot() cuma jalan sekali per worker — request()
    // di sini tidak mewakili request mana pun.
    // ponytail: nebeng request, bukan cron — kita tidak punya akses ke server.
    // Pindahkan ke Schedule kalau nanti cron di server sudah benar-benar jalan.
    Event::listen(function (RequestHandled $event) {
      if ($event->request->is('livewire/*')) {
        self::sweepStaleUploadTemps(sys_get_temp_dir());
      }
    });
  }

  /**
   * Hapus sisa upload PHP yang nyangkut (mis. /tmp/phpA7f3K2).
   *
   * PHP menaruh tiap file yang diupload di upload_tmp_dir (default /tmp) dan
   * baru menghapusnya kalau request selesai normal. Upload yang putus di
   * tengah jalan — timeout, koneksi drop, user menekan "coba lagi" — tidak
   * pernah dibersihkan siapa pun, menumpuk sampai /tmp penuh dan semua upload
   * berikutnya ikut gagal.
   *
   * Berjalan sebagai user PHP sendiri, jadi hanya menyentuh file miliknya.
   * Kalau php-fpm/FrankenPHP memakai PrivateTmp, sys_get_temp_dir() sudah
   * menunjuk ke /tmp privat itu — justru satu-satunya cara mudah
   * membersihkannya.
   *
   * @param  int  $minAge  Umur minimum (detik) sebelum file dianggap sampah.
   *                       Default 1 jam supaya tidak membunuh upload yang
   *                       masih berjalan di request lain.
   * @return int  Jumlah file yang terhapus.
   */
  public static function sweepStaleUploadTemps(string $dir, int $minAge = 3600): int
  {
    $batas = time() - $minAge;
    $terhapus = 0;

    // Pola 'php' + 6 karakter acak, persis format tempnam() milik PHP, supaya
    // tidak menyenggol php-fpm.sock atau file lain yang kebetulan berawalan php.
    foreach (glob(rtrim($dir, '/') . '/php??????') ?: [] as $sisa) {
      $diubah = @filemtime($sisa);

      // filemtime() bisa false kalau request lain sudah menghapusnya duluan.
      if ($diubah !== false && $diubah < $batas && is_file($sisa) && @unlink($sisa)) {
        $terhapus++;
      }
    }

    return $terhapus;
  }
}
