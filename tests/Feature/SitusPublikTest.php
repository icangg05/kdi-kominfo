<?php

namespace Tests\Feature;

use App\Livewire\Berita\Daftar as DaftarBerita;
use App\Livewire\Dokumen\Daftar as DaftarDokumen;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\KategoriBerita;
use App\Models\KategoriDokumen;
use App\Models\Pegawai;
use App\Models\ProfilDinas;
use App\Models\Video;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Situs publik dirender komponen Livewire penuh. Kesalahan view hanya muncul saat halaman
 * benar-benar dirender, jadi tiap halaman dibuka di sini.
 */
class SitusPublikTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed();
  }

  public static function halamanSitus(): array
  {
    return [
      'beranda' => ['/'],
      'berita' => ['/berita'],
      'dokumen' => ['/dokumen'],
      'galeri foto' => ['/galeri'],
      'galeri video' => ['/galeri/video'],
      'tentang kami' => ['/profil-dinas/tentang-kami'],
      'tupoksi' => ['/profil-dinas/tupoksi'],
      'profil pimpinan' => ['/profil-dinas/profil-pimpinan'],
      'profil pegawai' => ['/profil-dinas/profil-pegawai'],
      'struktur organisasi' => ['/profil-dinas/struktur-organisasi'],
      'layanan subdomain' => ['/layanan/subdomain-hosting'],
      'layanan email' => ['/layanan/email-dinas'],
      'layanan tte' => ['/layanan/pengajuan-tte'],
      'layanan 112' => ['/layanan/telpon-darurat-112'],
      'cek pengajuan' => ['/layanan/cek-pengajuan'],
    ];
  }

  #[\PHPUnit\Framework\Attributes\DataProvider('halamanSitus')]
  public function test_halaman_situs_dapat_dibuka(string $jalur): void
  {
    $this->get($jalur)
      ->assertOk()
      ->assertSee('Lambang Kota Kendari')
      ->assertSee('/img/komdigi-putih.webp');
  }

  public function test_tautan_halaman_situs_berpindah_lewat_wire_navigate(): void
  {
    $this->actingAs(\App\Models\User::factory()->create());

    $this->get('/')
      ->assertSee('href="/berita" wire:navigate', false)
      ->assertSee('href="/layanan/pengajuan-tte" wire:navigate', false)
      // Panel admin bukan halaman Livewire situs, jadi dimuat biasa.
      ->assertSee('href="/admin" class=', false)
      ->assertDontSee('href="/admin" wire:navigate', false);
  }

  public function test_logo_lama_tidak_dipakai_lagi(): void
  {
    $this->get('/')->assertDontSee('kominfo-logo');
  }

  public function test_kategori_kosong_disembunyikan(): void
  {
    KategoriBerita::create(['nama' => 'Kosong Berita', 'slug' => 'kosong-berita']);
    KategoriDokumen::create(['nama' => 'Kosong Dokumen', 'slug' => 'kosong-dokumen']);

    $this->get('/berita')->assertDontSee('Kosong Berita')->assertSee(Berita::first()->kategori->nama);
    $this->get('/dokumen')->assertDontSee('Kosong Dokumen')->assertSee(Dokumen::first()->kategori->nama);
  }

  public function test_survei_mengikuti_saklar_pengaturan(): void
  {
    $this->get('/')->assertSee('dialog-survei');

    $baris = ProfilDinas::where('jenis', 'pengaturan')->first();
    $baris->update(['konten' => collect($baris->konten)->map(fn ($p) => $p['id'] === 'survei_aktif' ? [...$p, 'value' => false] : $p)->all()]);

    $this->get('/')->assertDontSee('dialog-survei');
  }

  public function test_struktur_organisasi_digambar_sebagai_bagan(): void
  {
    $this->get('/profil-dinas/struktur-organisasi')
      ->assertOk()
      ->assertSee('class="bagan"', false)
      ->assertSee('Kepala Dinas');
  }

  public function test_pejabat_bagan_diambil_dari_data_pegawai(): void
  {
    $pegawai = Pegawai::first();
    $bagan = ProfilDinas::konten('bagan-organisasi');
    $bagan['pegawai_id'] = $pegawai->id;
    $bagan['anak'][0]['anak'][0]['anak'] = [['nama' => 'Staf Uji', 'pegawai_id' => $pegawai->id]];
    // Pegawai yang sudah dihapus: kotaknya tetap tampil tanpa nama pejabat.
    $bagan['anak'][0]['anak'][1]['pegawai_id'] = 999999;
    ProfilDinas::where('jenis', 'bagan-organisasi')->first()->update(['konten' => $bagan]);

    $this->get('/profil-dinas/struktur-organisasi')->assertOk()->assertSee('Staf Uji')->assertSee($pegawai->nama);

    $pegawai->update(['nama' => 'Nama Baru Pejabat']);
    $this->get('/profil-dinas/struktur-organisasi')->assertSee('Nama Baru Pejabat');
  }

  public function test_gambar_yang_berkasnya_hilang_memakai_sampul_bawaan(): void
  {
    Storage::fake('public');
    Storage::disk('public')->put('berita/ada.webp', 'isi');
    $ada = Berita::latest('tanggal')->latest('id')->first();
    $ada->update(['thumbnail' => 'berita/ada.webp']);
    Berita::whereKeyNot($ada->id)->update(['thumbnail' => 'berita/hilang.webp']);

    $this->get('/berita')
      ->assertSee('/storage/berita/ada.webp')
      ->assertDontSee('/storage/berita/hilang.webp')
      ->assertSee('/img/gambar-default.webp');
  }

  public function test_rincian_berita_tanpa_berkas_sampul_memakai_sampul_bawaan(): void
  {
    Storage::fake('public');
    $berita = Berita::first();
    $berita->update(['thumbnail' => 'berita/hilang.webp']);

    $this->get("/berita/{$berita->slug}")
      ->assertOk()
      ->assertDontSee('/storage/berita/hilang.webp')
      ->assertSee('<img src="/img/gambar-default.webp" alt="" aria-hidden="true" fetchpriority="high"', false);
  }

  public function test_beranda_memuat_berita_terbaru(): void
  {
    $terbaru = Berita::latest('tanggal')->latest('id')->first();

    $this->get('/')->assertOk()->assertSee($terbaru->judul)->assertSee('/berita/' . $terbaru->slug);
  }

  public function test_sitemap_memuat_semua_berita(): void
  {
    $respons = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=utf-8');

    foreach (Berita::pluck('slug') as $slug) {
      $respons->assertSee('/berita/' . rawurlencode($slug), false);
    }

    $respons->assertSee('/layanan/email-dinas')->assertDontSee('/layanan/cek-pengajuan');
  }

  public function test_robots_menunjuk_sitemap(): void
  {
    $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('/sitemap.xml');
  }

  public function test_berita_dapat_disaring_dan_dicari(): void
  {
    $satu = Berita::with('kategori')->first();
    $lain = Berita::where('kategori_berita_id', '!=', $satu->kategori_berita_id)->first();

    $daftar = Livewire::test(DaftarBerita::class)
      ->call('pilihKategori', $satu->kategori->slug)
      ->assertSet('kategori', $satu->kategori->slug)
      ->viewData('daftar');
    $this->assertTrue(collect($daftar->items())->every(fn ($b) => $b->kategori_berita_id === $satu->kategori_berita_id));
    if ($lain) {
      $this->assertNotContains($lain->id, collect($daftar->items())->pluck('id'));
    }

    // Berita terbaru dipakai karena judul lama bisa jadi awalan judul lain ("Ke-1" cocok dengan "Ke-10")
    // dan terdorong ke halaman berikutnya.
    $baru = Berita::latest('id')->first();
    Livewire::test(DaftarBerita::class)->set('search', $baru->judul)->assertSee($baru->judul);

    Livewire::test(DaftarBerita::class)
      ->set('search', 'tidakadaberitasepertiini')
      ->assertSee('Tidak ada berita yang cocok');

    // Tautan lama (?kategori=&search=) tetap terbaca; berita terbaru kategori itu ada di halaman pertama.
    $terbaruKategori = Berita::where('kategori_berita_id', $satu->kategori_berita_id)->latest('tanggal')->latest('id')->first();
    $this->get('/berita?kategori=' . $satu->kategori->slug)->assertOk()->assertSee($terbaruKategori->judul);
  }

  public function test_halaman_berita_tidak_berulang_saat_tanggal_sama(): void
  {
    // Regresi: tanpa pemecah seri, berita bertanggal sama bisa muncul lagi di halaman berikutnya.
    Berita::query()->update(['tanggal' => now()->toDateString()]);

    $satu = collect(Livewire::test(DaftarBerita::class)->viewData('daftar')->items())->pluck('slug');
    $dua = collect(Livewire::withQueryParams(['page' => 2])->test(DaftarBerita::class)->viewData('daftar')->items())->pluck('slug');

    $this->assertNotEmpty($dua);
    $this->assertEmpty($satu->intersect($dua));
  }

  public function test_video_memakai_id_youtube_dari_berbagai_bentuk_tautan(): void
  {
    Video::create(['judul' => 'Pendek', 'tanggal' => '2026-09-01', 'tautan' => 'https://youtu.be/dQw4w9WgXcQ?si=abc']);
    Video::create(['judul' => 'Tonton', 'tanggal' => '2026-09-02', 'tautan' => 'https://www.youtube.com/watch?feature=share&v=aqz-KE-bpKQ']);
    Video::create(['judul' => 'Bukan YouTube', 'tanggal' => '2026-09-03', 'tautan' => 'https://example.com/video']);

    $this->get('/galeri/video')
      ->assertOk()
      ->assertSee('data-video="aqz-KE-bpKQ"', false)
      ->assertSee('data-video="dQw4w9WgXcQ"', false)
      // Tanpa id YouTube: tautan biasa ke situs aslinya, tanpa pemutar di dialog.
      ->assertSee('href="https://example.com/video"', false);
  }

  public function test_membuka_berita_menambah_penghitung_dibaca(): void
  {
    $berita = Berita::first();
    $awal = $berita->total_lihat;

    $this->get('/berita/' . $berita->slug)->assertOk()->assertSee($berita->judul);

    $this->assertSame($awal + 1, $berita->fresh()->total_lihat);
  }

  public function test_berita_tak_dikenal_menghasilkan_404_berlayout_situs(): void
  {
    $this->get('/berita/slug-yang-tidak-ada')
      ->assertNotFound()
      ->assertSee('Halaman tidak ditemukan')
      ->assertSee('Lambang Kota Kendari');
  }

  public function test_layanan_tak_dikenal_menghasilkan_404(): void
  {
    $this->get('/layanan/entah-apa')->assertNotFound();
    $this->get('/halaman-yang-tidak-ada')->assertNotFound();
  }

  public function test_daftar_json_disajikan_sebagai_nilai_datar(): void
  {
    // `misi` disimpan sebagai [{id, value}] tapi tampil sebagai teks biasa.
    $misi = ProfilDinas::nilai('misi');

    $this->assertNotEmpty($misi);
    $this->get('/profil-dinas/tentang-kami')->assertOk()->assertSee($misi[0]);
  }

  public function test_situs_bertahan_saat_baris_pengaturan_belum_ada(): void
  {
    ProfilDinas::where('jenis', 'pengaturan')->delete();

    $this->get('/')->assertOk()->assertDontSee('dialog-survei');
  }

  public function test_rincian_dokumen_menyertakan_tanggal_dan_ukuran_berkas(): void
  {
    Storage::fake('public');

    $dokumen = Dokumen::latest()->latest('id')->first();
    $rincian = json_decode($dokumen->rincian(), true);
    $this->assertSame('–', $rincian['ukuran']);
    $this->assertNull($rincian['unduh']);
    $this->assertNotSame('–', $rincian['tanggal']);

    Storage::disk('public')->put($dokumen->file, 'isi berkas');
    $rincian = json_decode($dokumen->rincian(), true);
    $this->assertSame('10 B', $rincian['ukuran']);
    $this->assertSame('/download/' . $dokumen->slug, $rincian['unduh']);
  }

  public function test_dokumen_dapat_disaring_kategori(): void
  {
    $dokumen = Dokumen::with('kategori')->first();

    $daftar = Livewire::test(DaftarDokumen::class)->call('pilihKategori', $dokumen->kategori->slug)->viewData('daftar');

    $this->assertTrue(collect($daftar->items())->every(fn ($d) => $d->kategori_dokumen_id === $dokumen->kategori_dokumen_id));
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

    Storage::disk('public')->put($kembar->file, 'isi berkas');
    $this->get('/dokumen')->assertSee('/download/' . $kembar->slug);
    $this->get('/download/' . $kembar->id)->assertNotFound();

    $this->get('/download/' . $kembar->slug)
      ->assertOk()
      ->assertDownload($kembar->slug . '.' . pathinfo($kembar->file, PATHINFO_EXTENSION));

    // Judul diubah: slug ikut berganti; tidak bentrok dengan slug miliknya sendiri saat disimpan ulang.
    $kembar->update(['judul' => 'Rencana Strategis 2025–2029']);
    $this->assertSame('rencana-strategis-2025-2029', $kembar->slug);
    $kembar->update(['deskripsi' => 'baru']);
    $this->assertSame('rencana-strategis-2025-2029', $kembar->fresh()->slug);
  }

  public function test_pengunjung_dihitung_dari_halaman_situs_saja(): void
  {
    Visitor::query()->delete();

    $this->get('/')->assertOk();
    $this->get('/berita')->assertOk();
    $this->assertSame(1, Visitor::count(), 'Satu IP dihitung sekali per hari.');

    Visitor::query()->delete();
    $this->get('/admin/login');
    $this->get('/robots.txt');
    $this->assertSame(0, Visitor::count());
  }

  public function test_situs_selalu_terang_tanpa_tombol_mode_gelap(): void
  {
    $this->get('/')
      ->assertOk()
      ->assertSee('<meta name="color-scheme" content="only light" />', false)
      ->assertDontSee('data-tombol-tema', false)
      ->assertDontSee('Mode gelap');
  }

  public function test_layar_pembuka_hanya_di_beranda(): void
  {
    $this->get('/')
      ->assertOk()
      ->assertSeeInOrder(['data-layar-pembuka', 'Pemerintah Kota Kendari', 'Dinas Komunikasi dan Informatika', ProfilDinas::pengaturan()['alamat'], 'id="konten"'], false);

    $this->get('/berita')->assertOk()->assertDontSee('data-layar-pembuka', false);
  }
}
