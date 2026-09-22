<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('berita', function (Blueprint $table) {
      // Kosong untuk berita yang diinput manual.
      $table->unsignedBigInteger('wp_id')->nullable()->unique()->after('id');
      $table->string('wp_url')->nullable()->after('wp_id');
    });
  }

  public function down(): void
  {
    Schema::table('berita', function (Blueprint $table) {
      $table->dropUnique(['wp_id']);
      $table->dropColumn(['wp_id', 'wp_url']);
    });
  }
};
