<?php

use App\Models\ProfilDinas;
use Illuminate\Database\Migrations\Migration;

/** Survei kepuasan dulu tertulis di config/app.php; kini kunci `survei_aktif` dan `survei_url` di baris pengaturan agar bisa diubah dari admin. */
return new class extends Migration
{
  private const KUNCI = ['survei_aktif', 'survei_url'];

  public function up(): void
  {
    $baris = ProfilDinas::where('jenis', 'pengaturan')->first();

    if (! $baris || array_key_exists('survei_url', ProfilDinas::pengaturan())) {
      return;
    }

    $baris->konten = [
      ...(array) $baris->konten,
      ['id' => 'survei_aktif', 'value' => true],
      ['id' => 'survei_url', 'value' => 'https://surveidigital.spbe.go.id/embed/survey/eyJzdXJ2ZXlfaWQiOjIsInNlcnZpY2VfaWQiOjg2NSwiaG9zdCI6Imh0dHBzOi8vc3BwZC5rZW5kYXJpa290YS5nby5pZC8saHR0cDovL2xvY2FsaG9zdDo4MDA0Iiwia2V5Ijoia0NFZW9ySGgifQ==/embed/view/?jenis_layanan=SPPD%20Kota%20Kendari'],
    ];
    $baris->save();
  }

  public function down(): void
  {
    $baris = ProfilDinas::where('jenis', 'pengaturan')->first();

    if ($baris) {
      $baris->konten = array_values(array_filter((array) $baris->konten, fn ($item) => ! in_array($item['id'] ?? null, self::KUNCI, true)));
      $baris->save();
    }
  }
};
