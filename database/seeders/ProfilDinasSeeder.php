<?php

namespace Database\Seeders;

use App\Models\ProfilDinas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilDinasSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    ProfilDinas::insert([
      [
        'jenis'  => 'sambutan-kadis',
        'konten' => '<p>Sebagai institusi yang bertanggung jawab dalam bidang teknologi informasi dan komunikasi, kami berkomitmen untuk memberikan layanan terbaik guna mendukung transformasi digital dan mewujudkan Kota Kendari sebagai Smart City yang maju, inovatif, dan berdaya saing.</p><p>Website ini hadir sebagai sarana informasi dan komunikasi antara pemerintah dan masyarakat. Kami mengajak seluruh masyarakat Kota Kendari untuk bersama-sama memanfaatkan teknologi informasi guna menciptakan kota yang lebih cerdas, terhubung, dan sejahtera.</p><p>Dengan semangat kolaborasi dan inovasi, mari kita wujudkan visi Kota Kendari sebagai pusat pertumbuhan ekonomi digital dan teknologi di wilayah Indonesia Timur.</p>'
      ],
      [
        'jenis'  => 'tagline-sambutan',
        'konten' =>  '[
          "Smart City Terdepan",
          "Transformasi Digital",
          "Inovasi Teknologi",
          "Layanan Publik Prima",
          "Keamanan Siber",
          "Literasi Digital"
        ]'
      ],
    ]);
  }
}
