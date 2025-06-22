<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('pegawai', function (Blueprint $table) {
      $table->id();
      $table->string('nama');
      $table->string('nip')->nullable();
      $table->string('foto')->nullable();
      $table->foreignId('jabatan_id')->constrained('jabatan')->onDelete('cascade');
      $table->date('tanggal_lahir')->nullable();
      $table->text('alamat')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pegawai');
  }
};
