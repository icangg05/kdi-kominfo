<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/** Alamat unduhan memakai slug judul (/download/{slug}), bukan id dokumen. */
return new class extends Migration
{
  public function up(): void
  {
    Schema::table('dokumen', function (Blueprint $table) {
      $table->string('slug')->nullable()->unique()->after('judul');
    });

    // Isi slug dokumen lama; judul kembar diberi akhiran -2, -3, dst. seperti Dokumen::slugUnik().
    $dipakai = [];
    DB::table('dokumen')->orderBy('id')->each(function ($d) use (&$dipakai) {
      $dasar = Str::slug($d->judul) ?: 'dokumen';
      $slug = $dasar;
      for ($i = 2; isset($dipakai[$slug]); $i++) {
        $slug = "{$dasar}-{$i}";
      }
      $dipakai[$slug] = true;
      DB::table('dokumen')->where('id', $d->id)->update(['slug' => $slug]);
    });
  }

  public function down(): void
  {
    Schema::table('dokumen', function (Blueprint $table) {
      $table->dropUnique(['slug']);
      $table->dropColumn('slug');
    });
  }
};
