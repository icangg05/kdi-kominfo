<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('pengajuan_layanan', function (Blueprint $table) {
      $table->id();
      // Dibagikan ke pemohon untuk cek status, mis. TTE-20261026-K7Q2.
      $table->string('nomor', 32)->unique();
      // Slug layanan: subdomain-hosting, email-dinas, pengajuan-tte (PengajuanLayanan::JENIS).
      $table->string('layanan', 40)->index();
      $table->string('instansi');
      $table->string('nama_pemohon');
      // Disimpan seragam 08xxxxxxxxxx (PengajuanLayanan::normalkanWhatsapp) supaya cek status cocok apa pun cara ketiknya.
      $table->string('whatsapp', 20);
      $table->text('keterangan')->nullable();
      // Rincian kebutuhan ada di surat permohonan. Path di disk privat `local`, bukan `public`,
      // jadi surat tidak bisa dibuka lewat /storage.
      $table->string('surat');
      $table->string('status', 20)->default('menunggu')->index();
      $table->text('catatan_admin')->nullable();
      $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamp('diverifikasi_pada')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('pengajuan_layanan');
  }
};
