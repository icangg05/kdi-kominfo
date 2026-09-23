<?php

use App\Models\ProfilDinas;
use Illuminate\Database\Migrations\Migration;

/** Alamat footer dulu tertulis di kode; kini kunci `alamat` di baris pengaturan agar bisa diubah dari admin. */
return new class extends Migration
{
  public function up(): void
  {
    $baris = ProfilDinas::where('jenis', 'pengaturan')->first();

    if (! $baris || array_key_exists('alamat', ProfilDinas::pengaturan())) {
      return;
    }

    $baris->konten = [
      ...(array) $baris->konten,
      ['id' => 'alamat', 'value' => 'Jl. Drs. H. Abdullah Silondae No. 5, Kendari, Sulawesi Tenggara'],
    ];
    $baris->save();
  }

  public function down(): void
  {
    $baris = ProfilDinas::where('jenis', 'pengaturan')->first();

    if ($baris) {
      $baris->konten = array_values(array_filter((array) $baris->konten, fn ($item) => ($item['id'] ?? null) !== 'alamat'));
      $baris->save();
    }
  }
};
