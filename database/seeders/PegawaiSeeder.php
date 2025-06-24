<?php

namespace Database\Seeders;

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
    $data = [];

    for ($i = 1; $i <= 25; $i++) {
      $data[] = [
        'nama'          => 'Pegawai ' . $i,
        'nip'           => '19850' . rand(10000000, 99999999),
        'foto'          => null,
        'jabatan_id'    => rand(1, 15), // asumsi JabatanSeeder sudah insert 15 data
        'tanggal_lahir' => now()->subYears(rand(25, 55))->subDays(rand(0, 365)),
        'alamat'        => 'Jl. Contoh Alamat No. ' . $i . ', Kendari',
        'created_at'    => now(),
        'updated_at'    => now(),
      ];
    }

    foreach ($data as $item)
      Pegawai::create($item);
  }
}
