<?php

namespace Database\Seeders;

use App\Models\ProfilPimpinan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilPimpinanSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    ProfilPimpinan::create([
      'nama'          => 'Sahuriyanto Meronda, SP., MM',
      'awal_periode'  => 2025,
      'akhir_periode' => 2029,
      'foto'          => '["foto-kadis/kadis-1.webp", "foto-kadis/kadis-3.webp", "foto-kadis/kadis-2.webp"]',
      'konten'        => '
        <h3>Profil Singkat</h3>
        <ul>
          <li><strong>Nama:</strong> Sahuriyanto Meronda, SP., MM</li>
          <li><strong>Jabatan:</strong> Kepala Dinas Komunikasi dan Informatika (Kominfo) Kota Kendari</li>
        </ul>

        <h3>Kegiatan Terkini</h3>
        <ul>
          <li><strong>Peluncuran layanan darurat <em>Call Center 112</em></strong> sebagai bagian dari 100 hari kerja Pemerintah Kota Kendari — terhubung ke sistem darurat nasional (Mei–Juni 2025)</li>
          <li><strong>Peresmian Command Center</strong> Kota Kendari bersama Wakil Wali Kota dan DPRD (23 Mei 2025)</li>
          <li><strong>Tim publikasi digital</strong> Diskominfo menyambut HUT RI ke‑79 dengan kegiatan literasi dan dokumentasi publik</li>
          <li><strong>Kunjungan dialogis di Looka Coffee Kendari</strong> bersama komunitas Gema Sultra (30 Mei 2025)</li>
        </ul>

        <h3>Inisiatif & Fokus</h3>
        <ul>
          <li>Integrasi layanan publik melalui teknologi—Call Center 112 bebas pulsa dan bisa diakses tanpa sinyal.</li>
          <li>Optimalisasi Command Center sebagai pusat kendali informasi Kota Kendari.</li>
          <li>Kolaborasi aktif dengan Kementerian Kominfo Republik Indonesia dan DPRD Kota Kendari.</li>
          <li>Peningkatan literasi digital melalui publikasi kegiatan dan pelatihan masyarakat.</li>
        </ul>
        ',
    ]);
  }
}
