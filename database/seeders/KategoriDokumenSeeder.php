<?php

namespace Database\Seeders;

use App\Models\KategoriDokumen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriDokumenSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $data = [
      ['nama' => 'Peraturan Daerah', 'slug' => 'peraturan-daerah'],
      ['nama' => 'Peraturan Walikota', 'slug' => 'peraturan-walikota'],
      ['nama' => 'SK Dinas', 'slug' => 'sk-dinas'],
      ['nama' => 'Laporan Kinerja', 'slug' => 'laporan-kinerja'],
      ['nama' => 'Rencana Strategis', 'slug' => 'rencana-strategis'],
      ['nama' => 'Dokumen Publik Lainnya', 'slug' => 'dokumen-publik-lainnya'],
    ];

    foreach ($data as $item)
      KategoriDokumen::create($item);
  }
}
