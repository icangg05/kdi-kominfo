<?php

namespace Tests\Feature;

use App\Filament\Pages\HalamanProfil;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StrukturOrganisasiTest extends TestCase
{
  use RefreshDatabase;

  public function test_satu_pegawai_hanya_untuk_satu_jabatan(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    [$a, $b] = Pegawai::take(2)->pluck('id')->all();

    $halaman = Livewire::test(HalamanProfil::class);
    $unit = array_key_first($halaman->get('data.bagan_organisasi.anak'));
    $subUnit = array_key_first($halaman->get("data.bagan_organisasi.anak.{$unit}.anak"));

    // Pimpinan dan sub unit memakai pegawai yang sama: keduanya ditandai.
    $halaman->set('data.bagan_organisasi.pegawai_id', $a)
      ->set("data.bagan_organisasi.anak.{$unit}.anak.{$subUnit}.pegawai_id", $a)
      ->call('save')
      ->assertHasFormErrors(['bagan_organisasi.pegawai_id', "bagan_organisasi.anak.{$unit}.anak.{$subUnit}.pegawai_id"]);

    $halaman->set("data.bagan_organisasi.anak.{$unit}.anak.{$subUnit}.pegawai_id", $b)
      ->call('save')
      ->assertHasNoFormErrors();
  }
}
