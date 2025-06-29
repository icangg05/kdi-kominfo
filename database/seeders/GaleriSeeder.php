<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $data = [
      [
        'judul'   => 'Pembahasan Rancangan Perwali Integrasi Sistem Layanan Digital',
        'tanggal' => '2024-09-04',
        'gambar'  => 'galeri/galeri-1.webp',
      ],
      [
        'judul'   => 'Musyawarah ke-X Orari Lokal Kendari Tahun 2024',
        'tanggal' => '2024-09-28',
        'gambar'  => 'galeri/galeri-2.webp',
      ],
      [
        'judul'   => 'Sosialisasi Aplkasi Chatina',
        'tanggal' => '2024-10-09',
        'gambar'  => 'galeri/galeri-3.webp',
      ],
      [
        'judul'   => 'Sosialisasi Aplikasi Chatina pada Masyarakat Wua-Wua',
        'tanggal' => '2024-10-11',
        'gambar'  => 'galeri/galeri-4.webp',
      ],
      [
        'judul'   => 'Kegiatan FGD Pemberdayaan Kemitraan Ekonomi dan Komunikasi Publik',
        'tanggal' => '2024-10-30',
        'gambar'  => 'galeri/galeri-5.webp',
      ],
      [
        'judul'   => 'Rapat Pembentukan Layanan Call Center 112',
        'tanggal' => '2025-05-28',
        'gambar'  => 'galeri/galeri-6.webp',
      ],
      [
        'judul'   => 'Pembahasan SDI dan SIPD antara Diskominfo Konawe dan Kendari',
        'tanggal' => '2025-06-12',
        'gambar'  => 'galeri/galeri-7.webp',
      ],
      [
        'judul'   => 'Semarak HUT ke-79 RI',
        'tanggal' => '2024-08-15',
        'gambar'  => 'galeri/galeri-8.webp',
      ],
      [
        'judul'   => 'Inspektorat Gelar Sosialisasi SPI',
        'tanggal' => '2024--6-30',
        'gambar'  => 'galeri/galeri-9.webp',
      ],
    ];

    foreach ($data as $item)
      Galeri::create($item);
  }
}
