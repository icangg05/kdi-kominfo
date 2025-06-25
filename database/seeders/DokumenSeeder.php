<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DokumenSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $kategoriIds = KategoriDokumen::pluck('id')->toArray();

    for ($i = 1; $i <= 40; $i++) {
      Dokumen::create([
        'kategori_dokumen_id' => fake()->randomElement($kategoriIds),
        'judul'               => 'Dokumen ' . $i,
        'deskripsi'           => fake()->paragraph(),
        'file'                => 'dokumen_' . str()->random(10) . '.pdf',
        'total_unduhan'       => fake()->numberBetween(0, 1000),
      ]);
    }
  }
}
