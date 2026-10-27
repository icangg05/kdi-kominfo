<?php

namespace Tests\Feature;

use App\Filament\Pages\Pengaturan;
use App\Models\ProfilDinas;
use App\Models\User;
use App\Support\Telegram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

/** Tab Notifikasi Telegram di Pengaturan Situs dan pengirimnya (App\Support\Telegram). */
class PengaturanTelegramTest extends TestCase
{
  use RefreshDatabase;

  private const TOKEN = '123456789:AAHk3abcdefghijklmnopqrstuvwxyz0123';

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
    $this->actingAs(User::first());
  }

  public function test_token_bot_disimpan_terenkripsi_dan_tampil_lagi_di_formulir(): void
  {
    Livewire::test(Pengaturan::class)
      ->set('data.telegram_aktif', true)
      ->set('data.telegram_token', self::TOKEN)
      ->set('data.telegram_chat_id', '@kominfokendari')
      ->call('save')
      ->assertHasNoErrors();

    $atur = ProfilDinas::pengaturan();
    $this->assertTrue($atur['telegram_aktif']);
    $this->assertSame('@kominfokendari', $atur['telegram_chat_id']);
    $this->assertStringNotContainsString(self::TOKEN, json_encode($atur));
    $this->assertSame(self::TOKEN, Telegram::buka($atur['telegram_token']));
    // Pengaturan lain di baris yang sama tetap utuh.
    $this->assertSame('0822-4618-8268', $atur['telp']);

    Livewire::test(Pengaturan::class)->assertSet('data.telegram_token', self::TOKEN);
  }

  public function test_token_dan_id_chat_wajib_dan_dicek_bila_notifikasi_dinyalakan(): void
  {
    Livewire::test(Pengaturan::class)
      ->set('data.telegram_aktif', true)
      ->set('data.telegram_token', '')
      ->set('data.telegram_chat_id', 'kominfo kendari')
      ->call('save')
      ->assertHasErrors(['data.telegram_token' => 'required', 'data.telegram_chat_id' => 'regex']);

    Livewire::test(Pengaturan::class)
      ->set('data.telegram_aktif', false)
      ->set('data.telegram_token', '')
      ->set('data.telegram_chat_id', '')
      ->call('save')
      ->assertHasNoErrors();
  }

  public function test_galat_bot_api_diterjemahkan_untuk_operator(): void
  {
    Http::fake([
      'api.telegram.org/bot000:salah/*' => Http::response(['ok' => false, 'error_code' => 401, 'description' => 'Unauthorized'], 401),
      'api.telegram.org/*' => Http::response(['ok' => false, 'error_code' => 400, 'description' => 'Bad Request: chat not found'], 400),
    ]);

    $this->assertStringStartsWith('Token bot tidak dikenali Telegram.', Telegram::kirim('000:salah', '-1001', 'Uji'));
    $this->assertStringStartsWith('Chat tidak ditemukan.', Telegram::kirim(self::TOKEN, '-1001', 'Uji'));
  }

  public function test_galat_koneksi_tidak_membocorkan_token(): void
  {
    Http::fake(fn () => throw new ConnectionException('cURL error 28: timed out for https://api.telegram.org/bot' . self::TOKEN . '/sendMessage'));

    $galat = Telegram::kirim(self::TOKEN, '-1001', 'Uji');

    $this->assertStringStartsWith('Tidak dapat terhubung ke Telegram', $galat);
    $this->assertStringNotContainsString(self::TOKEN, $galat);
  }

  public function test_token_yang_tak_bisa_dibuka_dianggap_kosong(): void
  {
    $this->assertNull(Telegram::buka('bukan-hasil-enkripsi'));
    $this->assertNull(Telegram::buka(null));
    $this->assertNull(Telegram::kunci('  '));
  }
}
