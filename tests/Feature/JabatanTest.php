<?php

namespace Tests\Feature;

use App\Filament\Resources\Jabatans\Pages\ListJabatans;
use App\Models\Jabatan;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
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

    // Tambah dan ubah jabatan lewat modal di halaman daftar.
    Livewire::test(ListJabatans::class)
      ->callAction('create', data: ['nama' => 'Staf Unik'])
      ->assertHasActionErrors(['nama' => 'unique']);

    Livewire::test(ListJabatans::class)
      ->callAction(TestAction::make('edit')->table($lain), data: ['nama' => 'Staf Unik'])
      ->assertHasActionErrors(['nama' => 'unique']);

    // Menyimpan tanpa mengganti nama tidak dianggap duplikat.
    Livewire::test(ListJabatans::class)
      ->callAction(TestAction::make('edit')->table($lain))
      ->assertHasNoActionErrors();
  }
}
