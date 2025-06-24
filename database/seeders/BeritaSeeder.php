<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $kategoriIds = KategoriBerita::pluck('id')->toArray();

    for ($i = 1; $i <= 25; $i++) {
      $judul = "Judul Berita Ke-$i";
      Berita::create([
        'kategori_berita_id' => $kategoriIds[array_rand($kategoriIds)],
        'judul'              => $judul,
        'slug'               => str()->slug($judul),
        'konten'             => "
                    <p><strong>Paragraf pembuka</strong> untuk berita ke-$i.</p>
                    <p>Lorem ipsum dolor sit amet, <em>consectetur</em> adipiscing elit. Integer nec odio.</p>
                    <ul>
                        <li>Point pertama berita $i</li>
                        <li>Point kedua berita $i</li>
                    </ul>
                    <p><a href=\"#\">Baca selengkapnya</a></p>
                ",
        'thumbnail' => null,
        'total_lihat' => rand(0, 100),
      ]);
    }
  }
}
