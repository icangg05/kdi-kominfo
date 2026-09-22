<?php

namespace Tests\Feature;

use App\Filament\Resources\Beritas\Pages\ListBeritas;
use App\Models\Berita;
use App\Models\User;
use App\Support\SinkronBerita;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SinkronBeritaTest extends TestCase
{
  use RefreshDatabase;

  /** Isi "portal" palsu; fake di bawah meniru filter `after` WordPress. */
  private array $portal = [];

  protected function setUp(): void
  {
    parent::setUp();
    Storage::fake('public');

    Http::fake(function (Request $r) {
      if (str_contains($r->url(), '/wp-content/')) {
        $gambar = imagecreatetruecolor(40, 30);
        ob_start();
        imagejpeg($gambar);

        return Http::response(ob_get_clean());
      }

      parse_str((string) parse_url($r->url(), PHP_URL_QUERY), $q);

      return Http::response(collect($this->portal)
        ->filter(fn (array $p) => ! isset($q['after']) || $p['date'] > $q['after'])
        ->values()
        ->all());
    });
  }

  private function pos(int $id, string $tanggal, string $media = 'https://berita.kendarikota.go.id/wp-content/uploads/a.jpg'): array
  {
    return [
      'id' => $id,
      'date' => $tanggal,
      'link' => "https://berita.kendarikota.go.id/pos-{$id}/",
      'title' => ['rendered' => "Diskominfo &#8211; Kegiatan {$id}"],
      'content' => ['rendered' => '<p>Isi berita</p><script>alert(1)</script>'],
      '_embedded' => ['wp:featuredmedia' => [['source_url' => $media]]],
    ];
  }

  public function test_sinkron_pertama_menyimpan_berita_beserta_sampulnya(): void
  {
    $this->portal = [$this->pos(10, '2026-09-20T09:00:00'), $this->pos(11, '2026-09-21T09:00:00')];

    // Dua kata kunci mengembalikan pos yang sama; tetap tersimpan sekali.
    $this->assertSame(2, SinkronBerita::jalankan());

    $berita = Berita::with('kategori')->where('wp_id', 11)->first();
    $this->assertSame('Diskominfo – Kegiatan 11', $berita->judul);
    $this->assertSame(config('app.berita_wp.kategori'), $berita->kategori->nama);
    $this->assertSame('https://berita.kendarikota.go.id/pos-11/', $berita->wp_url);
    $this->assertStringNotContainsString('<script', $berita->konten);
    $this->assertStringEndsWith('.webp', $berita->thumbnail);
    Storage::disk('public')->assertExists($berita->thumbnail);
  }

  public function test_sinkron_berikutnya_hanya_mengambil_yang_lebih_baru(): void
  {
    $this->portal = [$this->pos(10, '2026-09-20T09:00:00')];
    SinkronBerita::jalankan();
    Berita::where('wp_id', 10)->delete();

    $this->portal[] = $this->pos(12, '2026-09-22T09:00:00');

    $this->assertSame(1, SinkronBerita::jalankan());
    $this->assertFalse(Berita::where('wp_id', 10)->exists(), 'Berita yang dihapus admin tidak boleh kembali.');
    Http::assertSent(fn (Request $r) => str_contains($r->url(), 'after=2026-09-20T09%3A00%3A00') && str_contains($r->url(), 'order=asc'));
  }

  public function test_sampul_dari_host_lain_tidak_diunduh(): void
  {
    $this->portal = [$this->pos(13, '2026-09-20T09:00:00', 'http://10.0.0.1/rahasia.jpg')];

    SinkronBerita::jalankan();

    $this->assertNull(Berita::where('wp_id', 13)->value('thumbnail'));
    Http::assertNotSent(fn (Request $r) => str_contains($r->url(), '10.0.0.1'));
  }

  public function test_tombol_admin_dan_jadwal_menjalankan_sinkron(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    $this->portal = [$this->pos(14, '2026-09-20T09:00:00')];

    Livewire::test(ListBeritas::class)
      ->callAction('sinkron')
      ->assertNotified('1 berita baru diambil');

    $jadwal = collect(app(Schedule::class)->events())->first(fn ($e) => str_contains($e->command, 'berita:sinkron'));
    $this->assertSame('0 */6 * * *', $jadwal->expression);
  }
}
