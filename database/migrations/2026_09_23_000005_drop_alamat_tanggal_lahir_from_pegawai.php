<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Alamat dan tanggal lahir pegawai tidak lagi diisi di admin maupun ditampilkan di situs. */
return new class extends Migration
{
  public function up(): void
  {
    Schema::table('pegawai', function (Blueprint $table) {
      $table->dropColumn(['tanggal_lahir', 'alamat']);
    });
  }

  public function down(): void
  {
    Schema::table('pegawai', function (Blueprint $table) {
      $table->date('tanggal_lahir')->nullable()->after('jabatan_id');
      $table->text('alamat')->nullable()->after('tanggal_lahir');
    });
  }
};
