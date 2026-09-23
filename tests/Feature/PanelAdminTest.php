<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Masuk;
use App\Filament\Widgets\RingkasanStats;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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
      'video' => ['/admin/videos'],
      'buat video' => ['/admin/videos/create'],
      'dokumen' => ['/admin/dokumens'],
      'buat dokumen' => ['/admin/dokumens/create'],
      'kategori dokumen' => ['/admin/kategori-dokumens'],
      'pegawai' => ['/admin/pegawais'],
      'buat pegawai' => ['/admin/pegawais/create'],
      'jabatan' => ['/admin/jabatans'],
      'profil pimpinan' => ['/admin/profil-pimpinans'],
      'isi halaman' => ['/admin/halaman-profil'],
      'pengaturan' => ['/admin/pengaturan'],
      'profil' => ['/admin/profil'],
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

  public function test_halaman_login_memakai_tata_letak_instansi(): void
  {
    $this->get('/admin/login')
      ->assertSuccessful()
      ->assertSee('Masuk ke Panel Admin')
      ->assertSee('Kelola informasi publik Kota Kendari');
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

  public function test_login_dibatasi_empat_percobaan_per_menit(): void
  {
    $this->seed();
    $masuk = Livewire::test(Masuk::class)->fillForm(['email' => 'bukan@admin.test', 'password' => 'jelas-salah']);

    foreach (range(1, 4) as $_) {
      $masuk->call('authenticate')->assertHasFormErrors(['email'])->assertNotNotified();
    }

    // Percobaan kelima tidak lagi dicek ke database, hanya notifikasi "terlalu banyak percobaan".
    $masuk->call('authenticate')->assertNotified();
  }

  public function test_menu_memakai_istilah_dashboard(): void
  {
    $this->seed();
    // Terjemahan bawaan Filament berbahasa Indonesia menyebutnya "Dasbor".
    app()->setLocale('id');

    $this->actingAs(User::first())->get('/admin')
      ->assertOk()
      ->assertSee('Dashboard')
      ->assertDontSee('Dasbor');
  }

  public function test_angka_dashboard_memakai_pemisah_ribuan(): void
  {
    $this->seed();
    app()->setLocale('id');
    Dokumen::query()->update(['total_unduhan' => 0]);
    Dokumen::first()->forceFill(['total_unduhan' => 12345])->save();

    Livewire::test(RingkasanStats::class)->assertSee('12.345 kali diunduh');
  }
}
