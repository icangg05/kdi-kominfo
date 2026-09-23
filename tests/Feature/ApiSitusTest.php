<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Pegawai;
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

  public function test_tautan_survei_mengikuti_saklar_config(): void
  {
    $this->getJson('/api/pengaturan')->assertJsonPath('survei', config('app.survei.url'));

    config(['app.survei.aktif' => false]);
    $this->getJson('/api/pengaturan')->assertJsonPath('survei', null);
  }

  public function test_struktur_organisasi_menyajikan_bagan_sebagai_pohon(): void
  {
    $this->getJson('/api/profil-dinas/struktur-organisasi')
      ->assertOk()
      ->assertJsonPath('bagan.nama', 'Kepala Dinas')
      ->assertJsonStructure(['bagan' => ['nama', 'pejabat', 'anak' => [['nama', 'pejabat', 'posisi', 'anak' => [['nama', 'pejabat']]]]], 'gambar']);
  }

  public function test_pejabat_bagan_diambil_dari_data_pegawai(): void
  {
    $pegawai = Pegawai::first();
    $bagan = ProfilDinas::konten('bagan-organisasi');
    $bagan['pegawai_id'] = $pegawai->id;
    $bagan['anak'][0]['anak'][0]['anak'] = [['nama' => 'Staf', 'pegawai_id' => $pegawai->id]];
    $bagan['anak'][0]['anak'][1]['pegawai_id'] = 999999;
    ProfilDinas::where('jenis', 'bagan-organisasi')->first()->update(['konten' => $bagan]);

    $this->getJson('/api/profil-dinas/struktur-organisasi')
      ->assertJsonPath('bagan.pejabat', $pegawai->nama)
      ->assertJsonPath('bagan.anak.0.anak.0.anak.0.pejabat', $pegawai->nama)
      ->assertJsonPath('bagan.anak.0.anak.1.pejabat', null)
      ->assertJsonMissingPath('bagan.pegawai_id');

    $pegawai->update(['nama' => 'Nama Baru']);
    $this->getJson('/api/profil-dinas/struktur-organisasi')->assertJsonPath('bagan.pejabat', 'Nama Baru');
  }

  public function test_gambar_yang_berkasnya_hilang_dikirim_null(): void
  {
    Storage::fake('public');
    Storage::disk('public')->put('berita/ada.webp', 'isi');
    $ada = Berita::latest('tanggal')->latest('id')->first();
    $ada->update(['thumbnail' => 'berita/ada.webp']);
    Berita::whereKeyNot($ada->id)->update(['thumbnail' => 'berita/hilang.webp']);

    $data = $this->getJson('/api/berita')->assertOk()->json('data');

    $this->assertSame('/storage/berita/ada.webp', $data[0]['thumbnail']);
    $this->assertNull($data[1]['thumbnail']);
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

  public function test_dokumen_menyertakan_tanggal_dan_ukuran_berkas(): void
  {
    Storage::fake('public');

    $dokumen = Dokumen::latest()->latest('id')->first();
    $this->getJson('/api/dokumen')->assertOk()
      ->assertJsonPath('data.0.id', $dokumen->id)
      ->assertJsonPath('data.0.tanggal', $dokumen->created_at->toIso8601String())
      ->assertJsonPath('data.0.ukuran', null);

    Storage::disk('public')->put($dokumen->file, 'isi berkas');
    $this->getJson('/api/dokumen')->assertJsonPath('data.0.ukuran', 10);
  }

  public function test_unduhan_menghitung_hanya_bila_berkas_ada(): void
  {
    Storage::fake('public');

    $dokumen = Dokumen::first();
    $awal = $dokumen->total_unduhan;

    // Berkas belum ada: harus 404 dan penghitung tidak bergerak.
    $this->get('/download/' . $dokumen->slug)->assertNotFound();
    $this->assertSame($awal, $dokumen->fresh()->total_unduhan);

    Storage::disk('public')->put($dokumen->file, 'isi berkas');

    $this->get('/download/' . $dokumen->slug)->assertOk();
    $this->assertSame($awal + 1, $dokumen->fresh()->total_unduhan);
  }

  public function test_unduhan_memakai_slug_judul_bukan_id(): void
  {
    Storage::fake('public');

    $asli = Dokumen::first();
    $kembar = $asli->replicate(['slug']);
    $kembar->save();
    $this->assertSame(str($asli->judul)->slug() . '-2', $kembar->slug);

    $this->getJson('/api/dokumen')->assertJsonPath('data.0.unduh', '/download/' . $kembar->slug);
    $this->get('/download/' . $kembar->id)->assertNotFound();

    Storage::disk('public')->put($kembar->file, 'isi berkas');
    $this->get('/download/' . $kembar->slug)
      ->assertOk()
      ->assertDownload($kembar->slug . '.' . pathinfo($kembar->file, PATHINFO_EXTENSION));

    // Judul diubah: slug ikut berganti; tidak bentrok dengan slug miliknya sendiri saat disimpan ulang.
    $kembar->update(['judul' => 'Rencana Strategis 2025–2029']);
    $this->assertSame('rencana-strategis-2025-2029', $kembar->slug);
    $kembar->update(['deskripsi' => 'baru']);
    $this->assertSame('rencana-strategis-2025-2029', $kembar->fresh()->slug);
  }
}
