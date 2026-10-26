<?php

namespace Tests\Feature;

use App\Filament\Resources\PengajuanLayanans\Pages\ListPengajuanLayanans;
use App\Filament\Resources\PengajuanLayanans\Pages\ViewPengajuanLayanan;
use App\Livewire\Layanan\CekPengajuan;
use App\Livewire\Layanan\FormPengajuan;
use App\Models\PengajuanLayanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class PengajuanLayananTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    Storage::fake('local');
    Storage::fake('public');
    RateLimiter::clear('pengajuan-layanan:127.0.0.1');
  }

  /** Aturan `mimes` menebak jenis dari isi berkas, jadi berkas palsu harus berawalan tanda tangan PDF. */
  private function pdf(string $nama = 'surat.pdf'): UploadedFile
  {
    return UploadedFile::fake()->createWithContent($nama, "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\ntrailer << /Root 1 0 R >>\n%%EOF\n");
  }

  /** Formulir minimal yang siap dikirim: data singkat pemohon dan surat permohonan. */
  private function formulirLengkap(string $layanan): Testable
  {
    return Livewire::test(FormPengajuan::class, ['layanan' => $layanan])
      ->set('instansi', 'Dinas Kesehatan Kota Kendari')
      ->set('nama_pemohon', 'Budi Santoso')
      ->set('whatsapp', '+62 812-3456-7890')
      ->set('surat', $this->pdf());
  }

  public static function layananDaring(): array
  {
    return [['subdomain-hosting'], ['email-dinas'], ['pengajuan-tte']];
  }

  #[\PHPUnit\Framework\Attributes\DataProvider('layananDaring')]
  public function test_pengajuan_tersimpan_dengan_nomor_dan_surat_di_disk_privat(string $layanan): void
  {
    $form = $this->formulirLengkap($layanan)->call('kirim')->assertHasNoErrors();

    $pengajuan = PengajuanLayanan::sole();
    $form->assertSet('terkirim', $pengajuan->nomor)->assertSee($pengajuan->nomor);

    $this->assertSame($layanan, $pengajuan->layanan);
    $this->assertSame('menunggu', $pengajuan->status);
    // Disimpan seragam supaya cek status cocok apa pun cara pemohon mengetiknya.
    $this->assertSame('081234567890', $pengajuan->whatsapp);
    $this->assertNull($pengajuan->keterangan);
    $this->assertMatchesRegularExpression('/^(DOM|EML|TTE)-\d{8}-[A-Z2-9]{4}$/', $pengajuan->nomor);
    $this->assertStringStartsWith('pengajuan/', $pengajuan->surat);
    Storage::disk('local')->assertExists($pengajuan->surat);
    Storage::disk('public')->assertDirectoryEmpty('/');
  }

  public function test_formulir_minimal_tanpa_data_pribadi(): void
  {
    $this->get('/layanan/pengajuan-tte')
      ->assertOk()
      ->assertSee('id="ajukan"', false)
      ->assertSee('Nomor WhatsApp')
      ->assertSee('Dipakai petugas Diskominfo untuk menghubungi Anda')
      ->assertSee('Surat permohonan')
      ->assertDontSee('KTP dan NPWP')
      ->assertDontSee('unggah-lampiran', false);

    $this->get('/layanan/telpon-darurat-112')->assertOk()->assertDontSee('id="ajukan"', false);
  }

  public function test_isian_wajib_dan_surat_divalidasi(): void
  {
    Livewire::test(FormPengajuan::class, ['layanan' => 'subdomain-hosting'])
      ->call('kirim')
      ->assertHasErrors(['instansi' => 'required', 'nama_pemohon' => 'required', 'whatsapp' => 'required', 'surat' => 'required'])
      ->assertHasNoErrors('keterangan');

    $this->assertSame(0, PengajuanLayanan::count());
  }

  public function test_nomor_whatsapp_harus_nomor_seluler(): void
  {
    foreach (['12345', '021 1234 5678', 'nomor saya'] as $salah) {
      $this->formulirLengkap('email-dinas')->set('whatsapp', $salah)->call('kirim')->assertHasErrors('whatsapp');
    }

    foreach (['0812 3456 7890', '62812-3456-7890', '8123456789'] as $benar) {
      $this->formulirLengkap('email-dinas')->set('whatsapp', $benar)->assertHasNoErrors('whatsapp');
    }
  }

  public function test_surat_hanya_menerima_dokumen_atau_gambar(): void
  {
    $this->formulirLengkap('email-dinas')
      ->set('surat', UploadedFile::fake()->createWithContent('surat.php', '<?php echo 1;'))
      ->call('kirim')
      ->assertHasErrors(['surat' => 'mimes']);
  }

  public function test_keterangan_tambahan_ikut_tersimpan(): void
  {
    $this->formulirLengkap('subdomain-hosting')->set('keterangan', 'Usulan dinkes.kendarikota.go.id')->call('kirim')->assertHasNoErrors();

    $this->assertSame('Usulan dinkes.kendarikota.go.id', PengajuanLayanan::sole()->keterangan);
  }

  public function test_perangkap_bot_tidak_menyimpan_apa_pun(): void
  {
    $this->formulirLengkap('email-dinas')->set('situs_web', 'http://spam.example')->call('kirim')->assertHasErrors('surat');

    $this->assertSame(0, PengajuanLayanan::count());
  }

  public function test_pengajuan_dibatasi_per_jaringan(): void
  {
    for ($i = 0; $i < FormPengajuan::BATAS_PER_JAM; $i++) {
      $this->formulirLengkap('email-dinas')->call('kirim')->assertHasNoErrors();
    }

    $this->formulirLengkap('email-dinas')->call('kirim')->assertHasErrors('surat');
    $this->assertSame(FormPengajuan::BATAS_PER_JAM, PengajuanLayanan::count());
  }

  public function test_cek_status_butuh_nomor_dan_whatsapp_yang_cocok(): void
  {
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $pengajuan = PengajuanLayanan::sole();

    Livewire::test(CekPengajuan::class)
      ->set('nomor', strtolower($pengajuan->nomor))
      ->set('whatsapp', '0899 9999 9999')
      ->call('cari')
      ->assertSee('Pengajuan tidak ditemukan')
      ->assertDontSee($pengajuan->instansi);

    // Cara ketik nomor berbeda dengan saat mengajukan tetap cocok.
    Livewire::test(CekPengajuan::class)
      ->set('nomor', strtolower($pengajuan->nomor))
      ->set('whatsapp', '0812-3456-7890')
      ->call('cari')
      ->assertSee($pengajuan->nomor)
      ->assertSee('Menunggu verifikasi');

    // Placeholder membantu pemohon mengenali format; halaman pribadi tidak diindeks.
    $this->get('/layanan/cek-pengajuan?nomor=' . $pengajuan->nomor)
      ->assertOk()
      ->assertSee('placeholder="Mis. TTE-20261026-K7Q2"', false)
      ->assertSee('placeholder="Mis. 0812 3456 7890"', false)
      ->assertSee('noindex', false);
  }

  public function test_admin_menyetujui_dan_menolak_pengajuan(): void
  {
    $this->seed();
    $admin = User::first();
    $this->actingAs($admin);

    $this->formulirLengkap('subdomain-hosting')->call('kirim');
    $this->formulirLengkap('email-dinas')->call('kirim');
    [$setuju, $tolak] = PengajuanLayanan::orderBy('id')->get();

    Livewire::test(ListPengajuanLayanans::class)->assertCanSeeTableRecords([$setuju, $tolak]);

    // Isian modal diisi lewat set(), jalur yang sama dengan wire:model di peramban; helper fillForm milik
    // Filament tidak mengisi modal aksi di halaman ViewRecord.
    Livewire::test(ViewPengajuanLayanan::class, ['record' => $setuju->getRouteKey()])
      ->assertSee($setuju->instansi)
      ->assertSee('https://wa.me/6281234567890')
      ->callAction('proses')
      ->mountAction('setujui')
      ->set('mountedActions.0.data.catatan_admin', 'Subdomain dinkes sudah aktif.')
      ->callMountedAction()
      ->assertHasNoActionErrors();

    $setuju->refresh();
    $this->assertSame('disetujui', $setuju->status);
    $this->assertSame('Subdomain dinkes sudah aktif.', $setuju->catatan_admin);
    $this->assertSame($admin->id, $setuju->diverifikasi_oleh);
    $this->assertNotNull($setuju->diverifikasi_pada);

    // Penolakan wajib beralasan, karena alasannya yang dibaca pemohon.
    Livewire::test(ViewPengajuanLayanan::class, ['record' => $tolak->getRouteKey()])
      ->mountAction('tolak')
      ->callMountedAction()
      ->assertHasActionErrors(['catatan_admin' => 'required'])
      ->set('mountedActions.0.data.catatan_admin', 'Surat belum ditandatangani.')
      ->callMountedAction()
      ->assertHasNoActionErrors();

    $this->assertSame('ditolak', $tolak->fresh()->status);

    Livewire::test(CekPengajuan::class)
      ->set('nomor', $tolak->nomor)
      ->set('whatsapp', $tolak->whatsapp)
      ->call('cari')
      ->assertSee('Ditolak')
      ->assertSee('Surat belum ditandatangani.');
  }

  public function test_surat_pengajuan_hanya_untuk_admin(): void
  {
    $this->seed();
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $pengajuan = PengajuanLayanan::sole();
    $alamat = route('filament.admin.pengajuan-surat', $pengajuan);

    $this->get($alamat)->assertRedirect('/admin/login');
    // Disk privat tidak bisa dibuka lewat /storage: rute `serve` disk local meminta tanda tangan URL.
    $this->get('/storage/' . $pengajuan->surat)->assertForbidden();

    $this->actingAs(User::first())->get($alamat)->assertOk()->assertHeader('Content-Type', 'application/pdf');
  }

  public function test_menghapus_pengajuan_ikut_menghapus_suratnya(): void
  {
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $pengajuan = PengajuanLayanan::sole();

    $pengajuan->delete();

    Storage::disk('local')->assertMissing($pengajuan->surat);
  }
}
