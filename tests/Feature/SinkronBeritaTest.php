<?php

namespace Tests\Feature;

use App\Filament\Resources\Beritas\Pages\ListBeritas;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\ProfilDinas;
use App\Models\User;
use App\Support\SinkronBerita;
use Filament\Schemas\Components\Text;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SinkronBeritaTest extends TestCase
{
  use RefreshDatabase;

  /** Isi "portal" palsu yang dikembalikan fake di bawah. */
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

      return Http::response($this->portal);
    });
  }

  /** Query string request jadi array; Request::data() tidak memuatnya untuk GET. */
  private function paramUrl(Request $r): array
  {
    parse_str((string) parse_url($r->url(), PHP_URL_QUERY), $q);

    return $q;
  }

  /** `$kategori` null meniru pos yang tidak berkategori di WordPress. */
  private function pos(int $id, string $tanggal, string $media = 'https://berita.kendarikota.go.id/wp-content/uploads/a.jpg', ?string $kategori = 'Berita'): array
  {
    return [
      'id' => $id,
      'date' => $tanggal,
      'link' => "https://berita.kendarikota.go.id/pos-{$id}/",
      'title' => ['rendered' => "Diskominfo &#8211; Kegiatan {$id}"],
      'content' => ['rendered' => '<p>Isi berita</p><script>alert(1)</script>'],
      '_embedded' => [
        'wp:featuredmedia' => [['source_url' => $media]],
        // Bentuk asli WordPress: array grup taksonomi, tiap grup array term.
        'wp:term' => $kategori === null ? [[]] : [
          [['taxonomy' => 'post_tag', 'name' => 'tag-abaikan']],
          [['taxonomy' => 'category', 'name' => $kategori]],
        ],
      ],
    ];
  }

  public function test_sinkron_pertama_menyimpan_berita_beserta_sampulnya(): void
  {
    $this->portal = [$this->pos(10, '2026-09-20T09:00:00'), $this->pos(11, '2026-09-21T09:00:00')];

    // Dua kata kunci mengembalikan pos yang sama; tetap tersimpan sekali.
    $this->assertSame(2, SinkronBerita::jalankan());

    $berita = Berita::with('kategori')->where('wp_id', 11)->first();
    $this->assertSame('Diskominfo – Kegiatan 11', $berita->judul);
    $this->assertSame('Berita', $berita->kategori->nama);
    $this->assertSame('https://berita.kendarikota.go.id/pos-11/', $berita->wp_url);
    $this->assertStringNotContainsString('<script', $berita->konten);
    $this->assertStringEndsWith('.webp', $berita->thumbnail);
    Storage::disk('public')->assertExists($berita->thumbnail);
  }

  public function test_berita_yang_sudah_ada_tidak_diambil_dua_kali(): void
  {
    $this->portal = [$this->pos(10, '2026-09-20T09:00:00')];
    SinkronBerita::jalankan();

    $this->portal[] = $this->pos(12, '2026-09-22T09:00:00');

    $this->assertSame(1, SinkronBerita::jalankan(), 'Hanya pos 12 yang baru; pos 10 sudah ada.');
    $this->assertSame(1, Berita::where('wp_id', 10)->count());
  }

  public function test_berita_yang_dihapus_muncul_lagi_saat_ditarik_ulang(): void
  {
    $this->portal = [$this->pos(10, '2026-09-20T09:00:00'), $this->pos(11, '2026-09-21T09:00:00')];
    SinkronBerita::jalankan();
    Berita::cursor()->each->delete();

    $this->assertSame(2, SinkronBerita::jalankan(), 'Berita yang dihapus harus bisa ditarik lagi.');
    $this->assertSame(2, Berita::count());
  }

  public function test_sampul_dari_host_lain_tidak_diunduh(): void
  {
    $this->portal = [$this->pos(13, '2026-09-20T09:00:00', 'http://10.0.0.1/rahasia.jpg')];

    SinkronBerita::jalankan();

    $this->assertNull(Berita::where('wp_id', 13)->value('thumbnail'));
    Http::assertNotSent(fn (Request $r) => str_contains($r->url(), '10.0.0.1'));
  }

  public function test_pencarian_dibatasi_ke_judul_agar_sama_dengan_halaman_portal(): void
  {
    $this->portal = [$this->pos(40, '2026-09-20T09:00:00')];

    SinkronBerita::jalankan();

    // Tanpa search_columns, API ikut mencari ke isi berita dan hasilnya berbeda
    // dari yang terlihat di /?s=kominfo.
    Http::assertSent(fn (Request $r) => ($this->paramUrl($r)['search_columns'] ?? null) === 'post_title');
  }

  public function test_kategori_dibuat_mengikuti_kategori_di_wordpress(): void
  {
    $this->portal = [
      $this->pos(20, '2026-09-20T09:00:00', kategori: 'Smart City'),
      $this->pos(21, '2026-09-21T09:00:00', kategori: 'Pengumuman'),
      $this->pos(22, '2026-09-22T09:00:00', kategori: 'Smart City'),
    ];

    SinkronBerita::jalankan();

    $this->assertSame('Smart City', Berita::with('kategori')->where('wp_id', 20)->first()->kategori->nama);
    $this->assertSame('Pengumuman', Berita::with('kategori')->where('wp_id', 21)->first()->kategori->nama);
    $this->assertSame('smart-city', KategoriBerita::where('nama', 'Smart City')->value('slug'));
    $this->assertSame(2, KategoriBerita::count(), 'Kategori yang sama tidak boleh dibuat dua kali.');
  }

  public function test_berita_tanpa_kategori_masuk_kategori_cadangan(): void
  {
    $this->portal = [$this->pos(23, '2026-09-20T09:00:00', kategori: null)];

    SinkronBerita::jalankan();

    $this->assertSame(
      config('app.berita_wp.kategori_cadangan'),
      Berita::with('kategori')->where('wp_id', 23)->first()->kategori->nama,
    );
  }

  public function test_pengaturan_admin_menimpa_config(): void
  {
    ProfilDinas::create(['jenis' => SinkronBerita::ATUR, 'konten' => [
      'kata_kunci' => ['smart city'],
      'maks_per_sinkron' => 1,
      'aktif' => true,
    ]]);
    $this->portal = [$this->pos(30, '2026-09-20T09:00:00'), $this->pos(31, '2026-09-21T09:00:00')];

    $this->assertSame(1, SinkronBerita::jalankan(), 'Batas dari admin harus dipakai, bukan 20 dari config.');
    // ?? null karena request unduh gambar sampul ikut terekam dan tidak punya query ini.
    Http::assertSent(fn (Request $r) => ($this->paramUrl($r)['search'] ?? null) === 'smart city' && ($this->paramUrl($r)['per_page'] ?? null) === '1');
    Http::assertNotSent(fn (Request $r) => ($this->paramUrl($r)['search'] ?? null) === 'kominfo');
  }

  public function test_modal_pengaturan_terisi_setelan_tersimpan(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    ProfilDinas::create(['jenis' => SinkronBerita::ATUR, 'konten' => [
      'kata_kunci' => ['diskominfo'],
      'maks_per_sinkron' => 5,
      'aktif' => false,
    ]]);

    Livewire::test(ListBeritas::class)
      ->mountAction('aturSinkron')
      // kata_kunci tidak ikut diperiksa di sini: state Repeater di-key UUID internal.
      // Bahwa nilainya benar-benar dipakai sudah diuji di test_pengaturan_admin_menimpa_config.
      ->assertSchemaStateSet(['maks_per_sinkron' => 5, 'aktif' => false]);
  }

  public function test_modal_menampilkan_tautan_pratinjau_sesuai_kata_kunci(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    ProfilDinas::create(['jenis' => SinkronBerita::ATUR, 'konten' => [
      'kata_kunci' => ['dinas kominfo'],
      'maks_per_sinkron' => 20,
      'aktif' => true,
    ]]);

    $lw = Livewire::test(ListBeritas::class)->mountAction('aturSinkron');

    // Isi modal Filament dirender di sisi klien, jadi tidak ada di HTML halaman;
    // helperText sendiri jadi komponen Text di dalam schema modal.
    $teks = collect($lw->instance()->getSchema('mountedActionSchema0')->getFlatComponents(withActions: false, withHidden: true))
      ->filter(fn ($c) => $c instanceof Text)
      ->map(fn (Text $c) => (string) $c->getContent())
      ->implode(' ');

    $this->assertStringContainsString('https://berita.kendarikota.go.id/?s=dinas+kominfo', $teks);
    $this->assertStringContainsString('1 sampai 20', $teks, 'Batas maksimal harus disebut di helper text.');
  }

  public function test_maks_per_sinkron_dibatasi_20(): void
  {
    $this->seed();
    $this->actingAs(User::first());

    $maks = Livewire::test(ListBeritas::class)
      ->mountAction('aturSinkron')
      ->instance()
      ->getSchema('mountedActionSchema0')
      ->getFlatComponents(withActions: false, withHidden: true)['maks_per_sinkron']
      ->getMaxValue();

    $this->assertSame(20, $maks);
  }

  public function test_sinkron_nonaktif_menyembunyikan_tombol_tapi_pengaturan_tetap_ada(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    ProfilDinas::create(['jenis' => SinkronBerita::ATUR, 'konten' => ['kata_kunci' => ['kominfo'], 'maks_per_sinkron' => 20, 'aktif' => false]]);

    Livewire::test(ListBeritas::class)
      ->assertActionHidden('sinkron')
      ->assertActionVisible('aturSinkron');
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
    $this->assertSame('0 3,9,15,21 * * *', $jadwal->expression);
  }

  public function test_sinkron_manual_tidak_menggeser_catatan_sinkron_otomatis(): void
  {
    $this->portal = [$this->pos(50, '2026-09-20T09:00:00')];
    SinkronBerita::jalankan();
    $otomatis = ProfilDinas::konten(SinkronBerita::STATUS)['dijalankan'];

    $this->travel(2)->hours();
    $this->portal[] = $this->pos(51, '2026-09-21T09:00:00');

    $this->assertSame(1, SinkronBerita::jalankan(otomatis: false), 'Sinkron manual tetap menarik berita.');
    $this->assertSame($otomatis, ProfilDinas::konten(SinkronBerita::STATUS)['dijalankan']);
  }

  public function test_halaman_admin_menampilkan_jadwal_berikutnya(): void
  {
    $this->seed();

    // Lewat HTTP, bukan Livewire::test: routes/console.php hanya dimuat di konteks CLI,
    // jadi keterangan jadwal tidak boleh bergantung pada daftar Schedule.
    $this->actingAs(User::first())
      ->get('/admin/beritas')
      ->assertOk()
      ->assertSee('Sinkron otomatis terakhir')
      ->assertSee('Berikutnya')
      ->assertDontSee('tidak terjadwal');
  }

  public function test_jadwal_berikutnya_jatuh_di_jam_tetap(): void
  {
    $this->travelTo(Carbon::parse('2026-09-23 15.05', config('app.timezone')));
    $this->assertSame('2026-09-23 21:00', SinkronBerita::berikutnya()->format('Y-m-d H:i'));

    // Sesudah jam terakhir, lompat ke jam pertama besok.
    $this->travelTo(Carbon::parse('2026-09-23 22.30', config('app.timezone')));
    $this->assertSame('2026-09-24 03:00', SinkronBerita::berikutnya()->format('Y-m-d H:i'));
  }
}
