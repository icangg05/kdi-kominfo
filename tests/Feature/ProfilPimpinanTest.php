<?php

namespace Tests\Feature;

use App\Filament\Resources\ProfilPimpinans\Pages\EditProfilPimpinan;
use App\Models\Pegawai;
use App\Models\ProfilPimpinan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfilPimpinanTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    Storage::fake('public');
    $this->seed();
    $this->actingAs(User::first());

    foreach (['a', 'b', 'c'] as $f) {
      Storage::disk('public')->put("foto-kadis/{$f}.webp", 'x');
    }
    ProfilPimpinan::first()->update(['foto' => ['foto-kadis/a.webp', 'foto-kadis/b.webp', 'foto-kadis/c.webp']]);
  }

  public function test_foto_yang_dilepas_ikut_terhapus_dari_disk(): void
  {
    Livewire::test(EditProfilPimpinan::class)
      ->set('data.foto', ['foto-kadis/a.webp', 'foto-kadis/c.webp'])
      ->call('save')
      ->assertHasNoFormErrors();

    Storage::disk('public')->assertExists(['foto-kadis/a.webp', 'foto-kadis/c.webp']);
    Storage::disk('public')->assertMissing('foto-kadis/b.webp');
  }

  public function test_foto_dibatasi_empat(): void
  {
    Livewire::test(EditProfilPimpinan::class)
      ->set('data.foto', array_map(fn ($i) => UploadedFile::fake()->image("{$i}.jpg"), range(1, 5)))
      ->call('save')
      ->assertHasFormErrors(['foto']);
  }

  public function test_nama_jabatan_dan_foto_utama_diambil_dari_pegawai(): void
  {
    Storage::disk('public')->put('pegawai/p.webp', 'x');
    $pegawai = Pegawai::with('jabatan')->first();
    $pegawai->update(['foto' => 'pegawai/p.webp']);
    ProfilPimpinan::first()->update(['pegawai_id' => $pegawai->id]);

    $this->getJson('/api/beranda')->assertOk()->assertJsonPath('kadis', [
      'nama' => $pegawai->nama,
      'jabatan' => $pegawai->jabatan->nama,
      'foto' => '/storage/pegawai/p.webp',
    ]);

    $this->getJson('/api/profil-pimpinan')->assertOk()
      ->assertJsonPath('foto', '/storage/pegawai/p.webp')
      ->assertJsonPath('fotoTambahan', ['/storage/foto-kadis/a.webp', '/storage/foto-kadis/b.webp', '/storage/foto-kadis/c.webp']);
  }

  public function test_pratinjau_foto_pegawai_mengikuti_pilihan_dan_default_bila_kosong(): void
  {
    Storage::disk('public')->put('pegawai/p.webp', 'x');
    $pegawai = Pegawai::whereKeyNot(ProfilPimpinan::first()->pegawai_id)->first();
    $pegawai->update(['foto' => 'pegawai/p.webp']);

    Livewire::test(EditProfilPimpinan::class)
      ->assertSeeHtml('/img/gambar-default.webp')
      ->set('data.pegawai_id', $pegawai->id)
      ->assertSeeHtml('/storage/pegawai/p.webp');
  }
}
