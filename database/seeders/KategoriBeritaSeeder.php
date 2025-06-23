<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriBeritaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('kategori_berita')->insert([
      ['nama' => 'Berita Utama', 'slug' => 'berita-utama'],
      ['nama' => 'Pengumuman', 'slug' => 'pengumuman'],
      ['nama' => 'Siaran Pers', 'slug' => 'siaran-pers'],
      ['nama' => 'Kegiatan Dinas', 'slug' => 'kegiatan-dinas'],
      ['nama' => 'Info Teknologi', 'slug' => 'info-teknologi'],
    ]);
  }
}
