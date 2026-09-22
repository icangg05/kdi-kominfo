<?php

namespace Tests\Unit;

use App\Support\KompresGambar;
use PHPUnit\Framework\TestCase;

class KompresGambarTest extends TestCase
{
  public function test_jpeg_lebar_diperkecil_dan_jadi_webp(): void
  {
    $gambar = imagecreatetruecolor(400, 300);
    for ($x = 0; $x < 400; $x += 4) {
      imagefilledrectangle($gambar, $x, 0, $x + 3, 299, random_int(0, 0xFFFFFF));
    }
    ob_start();
    imagejpeg($gambar, null, 100);
    $jpeg = ob_get_clean();

    $webp = KompresGambar::keWebp($jpeg, 200, 80);

    $this->assertSame(['RIFF', 'WEBP'], [substr($webp, 0, 4), substr($webp, 8, 4)]);
    [$lebar, $tinggi] = getimagesizefromstring($webp);
    $this->assertSame([200, 150], [$lebar, $tinggi]);
    $this->assertLessThan(strlen($jpeg), strlen($webp));
  }

  public function test_transparansi_png_tetap_ada(): void
  {
    $gambar = imagecreatetruecolor(400, 100);
    imagesavealpha($gambar, true);
    imagefill($gambar, 0, 0, imagecolorallocatealpha($gambar, 0, 0, 0, 127));
    ob_start();
    imagepng($gambar);
    $png = ob_get_clean();

    $hasil = imagecreatefromstring(KompresGambar::keWebp($png, 200, 80));

    $this->assertSame(127, (imagecolorat($hasil, 10, 10) >> 24) & 0x7F);
  }

  public function test_data_bukan_gambar_dikembalikan_null(): void
  {
    $this->assertNull(KompresGambar::keWebp('bukan gambar', 200, 80));
  }
}
