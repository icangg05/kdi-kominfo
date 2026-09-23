<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Nama dan jabatan pimpinan kini diambil dari data pegawai; foto beranda bisa dipilih. */
return new class extends Migration
{
  public function up(): void
  {
    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->foreignId('pegawai_id')->nullable()->after('id')->constrained('pegawai')->nullOnDelete();
      $table->unsignedTinyInteger('foto_beranda')->default(0)->after('foto');
    });

    // Nama lama dicocokkan ke pegawai. Kalau belum terdaftar, dibuatkan sebagai Kepala Dinas
    // supaya situs tetap menampilkan nama pimpinan tanpa menunggu admin memilih ulang.
    foreach (DB::table('profil_pimpinan')->get(['id', 'nama']) as $pimpinan) {
      $pegawaiId = DB::table('pegawai')->where('nama', $pimpinan->nama)->value('id')
        ?? DB::table('pegawai')->insertGetId([
          'nama' => $pimpinan->nama,
          'jabatan_id' => DB::table('jabatan')->where('nama', 'Kepala Dinas')->value('id')
            ?? DB::table('jabatan')->insertGetId(['nama' => 'Kepala Dinas', 'created_at' => now(), 'updated_at' => now()]),
          'created_at' => now(),
          'updated_at' => now(),
        ]);

      DB::table('profil_pimpinan')->where('id', $pimpinan->id)->update(['pegawai_id' => $pegawaiId]);
    }

    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->dropColumn('nama');
    });
  }

  public function down(): void
  {
    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->string('nama')->nullable()->after('id');
    });

    DB::table('profil_pimpinan')->update([
      'nama' => DB::raw('(select nama from pegawai where pegawai.id = profil_pimpinan.pegawai_id)'),
    ]);

    Schema::table('profil_pimpinan', function (Blueprint $table) {
      $table->dropConstrainedForeignId('pegawai_id');
      $table->dropColumn('foto_beranda');
    });
  }
};
