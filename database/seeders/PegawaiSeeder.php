<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Ambil id jabatan yang benar-benar ada; menebak 1..15 rusak begitu
    // AUTO_INCREMENT bergeser (mis. saat seeding kedua di dalam test).
    $jabatanIds = Jabatan::pluck('id')->all();

    $data = [];

    for ($i = 1; $i <= 25; $i++) {
      $data[] = [
        'nama'          => 'Pegawai ' . $i,
        'nip'           => '19850' . rand(10000000, 99999999),
        'foto'          => null,
        'jabatan_id'    => $jabatanIds[array_rand($jabatanIds)],
        'created_at'    => now(),
        'updated_at'    => now(),
      ];
    }

    foreach ($data as $item)
      Pegawai::create($item);
  }
}
