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
    User::create([
      'name'     => 'superadmin',
      'email'    => 'ilmifaizan1112@gamil.com',
      'password' => 'superadmin()123'
    ]);

    User::create([
      'name'     => 'admin',
      'email'    => 'diskominfokendari@gmail.com',
      'password' => 'admin123'
    ]);


    $this->call([
      JabatanSeeder::class,
      KategoriBeritaSeeder::class,
      KategoriDokumenSeeder::class,
      ProfilDinasSeeder::class,
      ProfilPimpinanSeeder::class
    ]);
  }
}
