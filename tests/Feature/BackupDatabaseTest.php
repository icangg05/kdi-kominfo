<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BackupDatabaseTest extends TestCase
{
  use RefreshDatabase;

  public function test_admin_mengunduh_dump_yang_bisa_dipulihkan(): void
  {
    $this->seed();
    $user = User::first();
    $user->update(['name' => "O'Neil"]);
    $jumlah = User::count();

    $sql = $this->actingAs($user)->get('/admin/backup-database')
      ->assertSuccessful()
      ->assertDownload()
      ->streamedContent();

    $this->assertStringContainsString('CREATE TABLE "users"', $sql);

    DB::table('users')->delete();
    DB::unprepared($sql);

    $this->assertSame($jumlah, User::count());
    $this->assertSame("O'Neil", User::find($user->id)->name);
  }

  public function test_menu_membuka_modal_konfirmasi(): void
  {
    $this->seed();

    $this->actingAs(User::first())->get('/admin')
      ->assertSee('href="#backup-database"', false)
      ->assertSee(['Nama database', DB::connection()->getDatabaseName(), 'Ukuran'])
      ->assertSee('href="' . route('filament.admin.backup-database') . '"', false);
  }

  public function test_tamu_tidak_bisa_mengunduh(): void
  {
    $this->get('/admin/backup-database')->assertRedirect('/admin/login');
  }
}
