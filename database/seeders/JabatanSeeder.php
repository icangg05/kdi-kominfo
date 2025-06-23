<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $data = [
      'Kepala Dinas',
      'Sekretaris Dinas',
      'Sub Bagian Umum',
      'Sub Bagian Kepegawaian & Keuangan',
      'Sub Bagian Perencanaan, Evaluasi & Pelaporan',
      'Bidang Pengelolaan Informasi & Komunikasi Publik',
      'Bidang Teknologi Informasi & e-Government',
      'Kasi Infrastruktur Jaringan',
      'Bidang Layanan Informasi, Komunikasi & Persandian',
      'Bidang Pengelolaan Data, Statistik & Integrasi Sistem Informasi',
      'Kasi Pengelolaan Data & Statistik',
      'Kasi Integrasi Sistem Informasi',
      'Pranata Komputer Ahli Muda',
      'Pranata Humas Ahli Muda',
      'Staff',
    ];

    foreach ($data as $item)
      Jabatan::create(['nama' => $item]);
  }
}
