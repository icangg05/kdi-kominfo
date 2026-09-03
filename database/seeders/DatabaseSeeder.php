<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Belum ada pembagian role: setiap akun punya akses penuh yang sama,
    // jadi satu akun saja. Kredensial diambil dari env agar tidak ikut ke repo.
    User::create([
      'name'     => 'Admin Diskominfo',
      'email'    => env('ADMIN_EMAIL', 'diskominfokendari@gmail.com'),
      'password' => env('ADMIN_PASSWORD', 'rahasia123'),
    ]);


    $this->call([
      JabatanSeeder::class,
      KategoriBeritaSeeder::class,
      KategoriDokumenSeeder::class,
      ProfilDinasSeeder::class,
      ProfilPimpinanSeeder::class,
      PegawaiSeeder::class,
      DokumenSeeder::class,
      BeritaSeeder::class,
      GaleriSeeder::class,
    ]);
  }
}
