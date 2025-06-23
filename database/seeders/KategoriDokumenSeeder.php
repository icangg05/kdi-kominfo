<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriDokumenSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('kategori_dokumen')->insert([
      ['nama' => 'Peraturan Daerah', 'slug' => 'peraturan-daerah'],
      ['nama' => 'Peraturan Walikota', 'slug' => 'peraturan-walikota'],
      ['nama' => 'SK Dinas', 'slug' => 'sk-dinas'],
      ['nama' => 'Laporan Kinerja', 'slug' => 'laporan-kinerja'],
      ['nama' => 'Rencana Strategis', 'slug' => 'rencana-strategis'],
      ['nama' => 'Dokumen Publik Lainnya', 'slug' => 'dokumen-publik-lainnya'],
    ]);
  }
}
