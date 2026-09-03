<?php

namespace Tests\Unit;

use App\Providers\AppServiceProvider;
use PHPUnit\Framework\TestCase;

class SweepStaleUploadTempsTest extends TestCase
{
  private string $dir;

  protected function setUp(): void
  {
    $this->dir = sys_get_temp_dir() . '/sweep-test-' . uniqid();
    mkdir($this->dir);
  }

  protected function tearDown(): void
  {
    foreach (glob($this->dir . '/*') ?: [] as $sisa) {
      unlink($sisa);
    }
    rmdir($this->dir);
  }

  private function buat(string $nama, int $umurDetik): string
  {
    $path = $this->dir . '/' . $nama;
    touch($path, time() - $umurDetik);

    return $path;
  }

  public function test_sisa_upload_yang_sudah_basi_dihapus(): void
  {
    $basi = $this->buat('phpA7f3K2', 7200); // 2 jam

    $this->assertSame(1, AppServiceProvider::sweepStaleUploadTemps($this->dir));
    $this->assertFileDoesNotExist($basi);
  }

  public function test_upload_yang_masih_berjalan_tidak_disentuh(): void
  {
    // Inilah yang bikin batas umur wajib ada: tanpa itu, sapuan ini akan
    // menghapus file 90MB milik request lain yang masih diupload.
    $berjalan = $this->buat('phpB8g4L3', 300); // 5 menit

    $this->assertSame(0, AppServiceProvider::sweepStaleUploadTemps($this->dir));
    $this->assertFileExists($berjalan);
  }

  public function test_file_lain_di_tmp_tidak_ikut_terhapus(): void
  {
    // Semua sudah basi; yang menyelamatkan mereka cuma pola nama.
    $lain = [
      $this->buat('php-fpm.sock', 7200),      // terlalu panjang
      $this->buat('phpAB12', 7200),           // terlalu pendek
      $this->buat('sess_9f2a1b7c', 7200),     // file sesi PHP
      $this->buat('laravel.log', 7200),
    ];

    $this->assertSame(0, AppServiceProvider::sweepStaleUploadTemps($this->dir));

    foreach ($lain as $path) {
      $this->assertFileExists($path);
    }
  }

  public function test_direktori_kosong_aman(): void
  {
    $this->assertSame(0, AppServiceProvider::sweepStaleUploadTemps($this->dir));
    $this->assertSame(0, AppServiceProvider::sweepStaleUploadTemps($this->dir . '/tidak-ada'));
  }
}
