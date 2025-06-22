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
        Schema::create('profil_pimpinan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->year('awal_periode');
            $table->year('akhir_periode');
            $table->text('foto')->nullable();
            $table->text('konten');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_pimpinan');
    }
};
