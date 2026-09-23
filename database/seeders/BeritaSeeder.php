<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
  /**
   * Isi contoh untuk pengembangan. Kategori dibuat sendiri di sini, bukan lewat seeder
   * terpisah: daftar kategori sebenarnya terbentuk otomatis dari kategori di portal
   * berita kota saat `php artisan berita:sinkron`.
   */
  public function run(): void
  {
    $kategori = KategoriBerita::firstOrCreate(['slug' => 'berita'], ['nama' => 'Berita']);

    for ($i = 1; $i <= 25; $i++) {
      $judul = "Judul Berita Ke-$i";
      Berita::create([
        'kategori_berita_id' => $kategori->id,
        'judul'              => $judul,
        'tanggal'            => now(),
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
