<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('pengajuan_layanan', function (Blueprint $table) {
      // Isian khusus tiap layanan (NIK, NIP, jabatan, TTL, subdomain, IP tujuan, ...); kuncinya di
      // PengajuanLayanan::JENIS[...]['rincian']. Kosong untuk pengajuan lama yang hanya bersurat.
      $table->json('rincian')->nullable()->after('whatsapp');
      // Hanya pengajuan TTE. Path di disk privat `local`, sama seperti surat.
      $table->string('ktp')->nullable()->after('surat');
    });
  }

  public function down(): void
  {
    Schema::table('pengajuan_layanan', function (Blueprint $table) {
      $table->dropColumn(['rincian', 'ktp']);
    });
  }
};
