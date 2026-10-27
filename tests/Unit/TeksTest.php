<?php

namespace Tests\Unit;

use App\Support\Teks;
use PHPUnit\Framework\TestCase;

class TeksTest extends TestCase
{
  public function test_huruf_awal_tiap_kata_kapital_tanpa_merusak_singkatan(): void
  {
    $this->assertSame('Dinas Kesehatan Kota Kendari', Teks::kapitalTiapKata('  dinas kesehatan   kota kendari '));
    $this->assertSame('Kepala UPTD Puskesmas', Teks::kapitalTiapKata('kepala UPTD puskesmas'));
    $this->assertSame('BUDI SANTOSO', Teks::kapitalTiapKata('BUDI SANTOSO'));
  }

  public function test_kata_tugas_di_tengah_tetap_huruf_kecil(): void
  {
    $this->assertSame('Dinas Pemberdayaan Perempuan dan Perlindungan Anak', Teks::kapitalTiapKata('dinas pemberdayaan perempuan dan perlindungan anak'));
    $this->assertSame('Dari Kendari', Teks::kapitalTiapKata('dari kendari'));
  }

  public function test_kalimat_cukup_huruf_pertama(): void
  {
    $this->assertSame("Portal informasi.\nuntuk masyarakat", Teks::kapitalAwal("  portal informasi.\nuntuk masyarakat "));
  }
}
