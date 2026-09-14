<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\ProfilDinas;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * API ini satu-satunya sumber data frontend Astro, jadi bentuk responsnya
 * adalah kontrak: kalau berubah diam-diam, situs publik ikut rusak.
 */
class ApiSitusTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
  }

  public function test_pengaturan_mengembalikan_kontak_sebagai_peta(): void
  {
    $this->getJson('/api/pengaturan')
      ->assertOk()
      ->assertJsonStructure(['pengaturan' => ['telp', 'email'], 'kategoriBerita', 'kategoriDokumen']);
  }

  public function test_beranda_memuat_berita_bukan_galeri(): void
  {
    $data = $this->getJson('/api/beranda')->assertOk()->json();

    $this->assertNotEmpty($data['berita']);
    // Regresi: versi lama mengambil daftar berita dari model Galeri.
    $this->assertArrayHasKey('slug', $data['berita'][0]);
    $this->assertArrayHasKey('hariIni', $data['pengunjung']);
  }

  public function test_sitemap_memuat_semua_berita(): void
  {
    $this->getJson('/api/sitemap')
      ->assertOk()
      ->assertJsonCount(Berita::count(), 'berita')
      ->assertJsonStructure(['berita' => [['slug', 'diubah']]]);
  }

  public function test_berita_dapat_disaring_dan_dicari(): void
  {
    $satu = Berita::with('kategori')->first();

    $this->getJson('/api/berita?kategori=' . $satu->kategori->slug)
      ->assertOk()
      ->assertJsonPath('data.0.kategori.slug', $satu->kategori->slug);

    // Berita terbaru dipakai karena judul lama bisa jadi awalan judul lain ("Ke-1" cocok dengan "Ke-10")
    // dan terdorong ke halaman berikutnya.
    $baru = Berita::latest('id')->first();

    $this->getJson('/api/berita?search=' . urlencode($baru->judul))
      ->assertOk()
      ->assertJsonFragment(['judul' => $baru->judul]);

    $this->getJson('/api/berita?search=tidakadaberitasepertiini')
      ->assertOk()
      ->assertJsonCount(0, 'data');
  }

  public function test_halaman_berita_tidak_berulang_saat_tanggal_sama(): void
  {
    // Regresi: tanpa pemecah seri, berita bertanggal sama bisa muncul lagi di halaman berikutnya.
    Berita::query()->update(['tanggal' => now()->toDateString()]);

    $satu = collect($this->getJson('/api/berita?page=1')->json('data'))->pluck('slug');
    $dua = collect($this->getJson('/api/berita?page=2')->json('data'))->pluck('slug');

    $this->assertNotEmpty($dua);
    $this->assertEmpty($satu->intersect($dua));
  }

  public function test_video_menyertakan_id_youtube_dari_berbagai_bentuk_tautan(): void
  {
    Video::create(['judul' => 'Pendek', 'tanggal' => '2026-09-01', 'tautan' => 'https://youtu.be/dQw4w9WgXcQ?si=abc']);
    Video::create(['judul' => 'Tonton', 'tanggal' => '2026-09-02', 'tautan' => 'https://www.youtube.com/watch?feature=share&v=aqz-KE-bpKQ']);
    Video::create(['judul' => 'Bukan YouTube', 'tanggal' => '2026-09-03', 'tautan' => 'https://example.com/video']);

    $this->getJson('/api/video')
      ->assertOk()
      ->assertJsonPath('data.0.youtubeId', null)
      ->assertJsonPath('data.1.youtubeId', 'aqz-KE-bpKQ')
      ->assertJsonPath('data.2.youtubeId', 'dQw4w9WgXcQ');
  }

  public function test_membuka_berita_menambah_penghitung_dibaca(): void
  {
    $berita = Berita::first();
    $awal = $berita->total_lihat;

    $this->getJson('/api/berita/' . $berita->slug)->assertOk();

    $this->assertSame($awal + 1, $berita->fresh()->total_lihat);
  }

  public function test_berita_tak_dikenal_menghasilkan_404(): void
  {
    $this->getJson('/api/berita/slug-yang-tidak-ada')->assertNotFound();
  }

  public function test_daftar_json_disajikan_sebagai_nilai_datar(): void
  {
    // `misi` dan `fungsi` disimpan sebagai [{id, value}] tapi harus keluar rata.
    $misi = $this->getJson('/api/profil-dinas/tentang-kami')->assertOk()->json('misi');

    $this->assertNotEmpty($misi);
    $this->assertIsString($misi[0]);
  }

  public function test_halaman_profil_tak_dikenal_menghasilkan_404(): void
  {
    $this->getJson('/api/profil-dinas/entah-apa')->assertNotFound();
  }

  public function test_pengaturan_bertahan_saat_baris_belum_ada(): void
  {
    ProfilDinas::where('jenis', 'pengaturan')->delete();

    $this->getJson('/api/pengaturan')->assertOk()->assertJsonPath('pengaturan', []);
  }

  public function test_unduhan_menghitung_hanya_bila_berkas_ada(): void
  {
    Storage::fake('public');

    $dokumen = Dokumen::first();
    $awal = $dokumen->total_unduhan;

    // Berkas belum ada: harus 404 dan penghitung tidak bergerak.
    $this->get('/download/' . $dokumen->id)->assertNotFound();
    $this->assertSame($awal, $dokumen->fresh()->total_unduhan);

    Storage::disk('public')->put($dokumen->file, 'isi berkas');

    $this->get('/download/' . $dokumen->id)->assertOk();
    $this->assertSame($awal + 1, $dokumen->fresh()->total_unduhan);
  }
}
