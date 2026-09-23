<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Foto beranda kini foto profil pegawai; foto di profil pimpinan hanya foto tambahan. */
return new class extends Migration
{
  public function up(): void
  {
    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->dropColumn('foto_beranda');
    });
  }

  public function down(): void
  {
    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->unsignedTinyInteger('foto_beranda')->default(0)->after('foto');
    });
  }
};
