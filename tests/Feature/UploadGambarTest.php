<?php

namespace Tests\Feature;

use App\Filament\Pages\HalamanProfil;
use App\Filament\Resources\Galeris\Pages\CreateGaleri;
use App\Models\Galeri;
use App\Models\ProfilDinas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class UploadGambarTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    Storage::fake('public');
    $this->seed();
    $this->actingAs(User::first());
  }

  public function test_gambar_galeri_dikompres_jadi_webp(): void
  {
    Livewire::test(CreateGaleri::class)
      ->set('data.judul', 'Rapat koordinasi')
      ->set('data.gambar', UploadedFile::fake()->image('rapat.jpg', 3000, 2000))
      ->call('create')
      ->assertHasNoFormErrors();

    $path = Galeri::latest('id')->first()->gambar;
    $this->assertStringEndsWith('.webp', $path);
    [$lebar] = getimagesizefromstring(Storage::disk('public')->get($path));
    $this->assertSame(config('app.upload.kompres_gambar.lebar_maks'), $lebar);
  }

  public function test_gambar_di_atas_batas_ditolak(): void
  {
    $jpeg = UploadedFile::fake()->image('besar.jpg', 10, 10)->getContent();
    $besar = $jpeg . str_repeat("\0", config('app.upload.gambar_maks_kb') * 1024);

    Livewire::test(CreateGaleri::class)
      ->set('data.judul', 'Rapat koordinasi')
      ->set('data.gambar', UploadedFile::fake()->createWithContent('besar.jpg', $besar))
      ->call('create')
      ->assertHasFormErrors(['gambar']);
  }

  public function test_bagan_resmi_pdf_disimpan_tanpa_kompres(): void
  {
    $pdf = UploadedFile::fake()->createWithContent('bagan.pdf', "%PDF-1.4\n%%EOF");

    Livewire::test(HalamanProfil::class)
      ->set('data.struktur_organisasi', $pdf)
      ->call('save')
      ->assertHasNoFormErrors();

    $path = ProfilDinas::konten('struktur-organisasi');
    $this->assertStringEndsWith('.pdf', $path);
    $this->assertSame("%PDF-1.4\n%%EOF", Storage::disk('public')->get($path));
  }
}
