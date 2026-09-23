<?php

namespace Tests\Feature;

use App\Filament\Resources\Jabatans\Pages\CreateJabatan;
use App\Filament\Resources\Jabatans\Pages\EditJabatan;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class JabatanTest extends TestCase
{
  use RefreshDatabase;

  public function test_nama_jabatan_harus_unik_saat_tambah_dan_ubah(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    Jabatan::create(['nama' => 'Staf Unik']);
    $lain = Jabatan::create(['nama' => 'Analis Unik']);

    Livewire::test(CreateJabatan::class)
      ->fillForm(['nama' => 'Staf Unik'])
      ->call('create')
      ->assertHasFormErrors(['nama' => 'unique']);

    Livewire::test(EditJabatan::class, ['record' => $lain->getRouteKey()])
      ->fillForm(['nama' => 'Staf Unik'])
      ->call('save')
      ->assertHasFormErrors(['nama' => 'unique']);

    // Menyimpan tanpa mengganti nama tidak dianggap duplikat.
    Livewire::test(EditJabatan::class, ['record' => $lain->getRouteKey()])
      ->call('save')
      ->assertHasNoFormErrors();
  }
}
