<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menjaga agar setiap halaman panel Filament benar-benar ter-render.
 * Kesalahan skema form/tabel hanya muncul saat halaman dirender, bukan saat kelasnya dimuat.
 */
class PanelAdminTest extends TestCase
{
  use RefreshDatabase;

  public static function halamanPanel(): array
  {
    return [
      'dasbor' => ['/admin'],
      'berita' => ['/admin/beritas'],
      'buat berita' => ['/admin/beritas/create'],
      'kategori berita' => ['/admin/kategori-beritas'],
      'galeri' => ['/admin/galeris'],
      'buat galeri' => ['/admin/galeris/create'],
      'dokumen' => ['/admin/dokumens'],
      'buat dokumen' => ['/admin/dokumens/create'],
      'kategori dokumen' => ['/admin/kategori-dokumens'],
      'pegawai' => ['/admin/pegawais'],
      'buat pegawai' => ['/admin/pegawais/create'],
      'jabatan' => ['/admin/jabatans'],
      'profil pimpinan' => ['/admin/profil-pimpinans'],
      'isi halaman' => ['/admin/halaman-profil'],
      'pengaturan' => ['/admin/pengaturan'],
    ];
  }

  #[\PHPUnit\Framework\Attributes\DataProvider('halamanPanel')]
  public function test_halaman_panel_dapat_dibuka(string $jalur): void
  {
    $this->seed();

    $this->actingAs(User::first())
      ->get($jalur)
      ->assertSuccessful();
  }

  public function test_tamu_diarahkan_ke_login(): void
  {
    $this->get('/admin')->assertRedirect('/admin/login');
  }

  public function test_akun_hasil_seeder_bisa_login(): void
  {
    $this->seed();

    // Satu akun saja: belum ada pembagian role, semua akses identik.
    $this->assertSame(1, User::count());

    $this->assertTrue(auth()->attempt([
      'email'    => env('ADMIN_EMAIL', 'diskominfokendari@gmail.com'),
      'password' => env('ADMIN_PASSWORD', 'rahasia123'),
    ]), 'Kredensial admin dari seeder gagal login.');
  }

  public function test_password_salah_ditolak(): void
  {
    $this->seed();

    $this->assertFalse(auth()->attempt([
      'email'    => env('ADMIN_EMAIL', 'diskominfokendari@gmail.com'),
      'password' => 'jelas-salah',
    ]));
  }
}
