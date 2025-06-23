<?php

namespace Database\Seeders;

use App\Models\KategoriBerita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriBeritaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $data = [
      ['nama' => 'Berita Utama', 'slug' => 'berita-utama'],
      ['nama' => 'Pengumuman', 'slug' => 'pengumuman'],
      ['nama' => 'Siaran Pers', 'slug' => 'siaran-pers'],
      ['nama' => 'Kegiatan Dinas', 'slug' => 'kegiatan-dinas'],
      ['nama' => 'Info Teknologi', 'slug' => 'info-teknologi'],
    ];

    foreach ($data as $item)
      KategoriBerita::create($item);
  }
}
