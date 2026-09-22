<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Profil;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ProfilTest extends TestCase
{
  use RefreshDatabase;

  private User $user;

  protected function setUp(): void
  {
    parent::setUp();
    $this->user = User::factory()->create(['password' => 'lama-rahasia']);
    $this->actingAs($this->user);
  }

  public function test_ubah_nama_dan_kata_sandi(): void
  {
    Livewire::test(Profil::class)
      ->set('data.name', 'Admin Baru')
      ->set('data.password', 'baru-rahasia')
      ->set('data.passwordConfirmation', 'baru-rahasia')
      ->set('data.currentPassword', 'lama-rahasia')
      ->call('save')
      ->assertHasNoFormErrors();

    $this->user->refresh();
    $this->assertSame('Admin Baru', $this->user->name);
    $this->assertTrue(Hash::check('baru-rahasia', $this->user->password));
  }

  public function test_kata_sandi_saat_ini_salah_ditolak(): void
  {
    Livewire::test(Profil::class)
      ->set('data.password', 'baru-rahasia')
      ->set('data.passwordConfirmation', 'baru-rahasia')
      ->set('data.currentPassword', 'tebakan')
      ->call('save')
      ->assertHasFormErrors(['currentPassword']);

    $this->assertTrue(Hash::check('lama-rahasia', $this->user->fresh()->password));
  }

  public function test_ganti_kata_sandi_wajib_isi_ketiga_kolom(): void
  {
    Livewire::test(Profil::class)
      ->set('data.password', 'baru-rahasia')
      ->call('save')
      ->assertHasFormErrors(['currentPassword' => 'required', 'passwordConfirmation' => 'required']);

    Livewire::test(Profil::class)
      ->set('data.passwordConfirmation', 'baru-rahasia')
      ->call('save')
      ->assertHasFormErrors(['password' => 'required', 'currentPassword' => 'required']);

    $this->assertTrue(Hash::check('lama-rahasia', $this->user->fresh()->password));
  }
}
