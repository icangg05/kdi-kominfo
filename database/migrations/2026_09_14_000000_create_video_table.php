<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('video', function (Blueprint $table) {
      $table->id();
      $table->string('judul');
      $table->date('tanggal');
      // Tautan YouTube; situs publik menyematkannya lewat youtube-nocookie.
      $table->string('tautan');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('video');
  }
};
