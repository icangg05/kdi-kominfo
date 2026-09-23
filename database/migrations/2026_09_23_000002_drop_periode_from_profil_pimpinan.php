<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Kepala dinas jabatan karier PNS, bukan jabatan politik, jadi tidak punya periode. */
return new class extends Migration
{
  public function up(): void
  {
    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->dropColumn(['awal_periode', 'akhir_periode']);
    });
  }

  public function down(): void
  {
    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->year('awal_periode')->nullable()->after('nama');
      $table->year('akhir_periode')->nullable()->after('awal_periode');
    });
  }
};
