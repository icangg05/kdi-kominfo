<?php

namespace Tests\Feature;

use App\Filament\Resources\PengajuanLayanans\Pages\ListPengajuanLayanans;
use App\Filament\Resources\PengajuanLayanans\Pages\ViewPengajuanLayanan;
use App\Livewire\Layanan\CekPengajuan;
use App\Livewire\Layanan\FormPengajuan;
use App\Models\PengajuanLayanan;
use App\Models\ProfilDinas;
use App\Models\User;
use App\Support\Situs;
use App\Support\Telegram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
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

  /** Isian khusus tiap layanan yang sah, untuk formulirLengkap(). */
  private const RINCIAN = [
    'subdomain-hosting' => [
      'subdomain' => 'dinkes',
      'jenis_layanan' => 'publik',
      'ip_tujuan' => '103.150.12.34',
      'lokasi_server' => 'Pusat Data Diskominfo',
      'deskripsi' => 'Portal informasi layanan kesehatan.',
    ],
    'email-dinas' => [
      'nip' => '198503122010011002',
      'tempat_lahir' => 'Kendari',
      'tanggal_lahir' => '1985-03-12',
    ],
    'pengajuan-tte' => [
      'nik' => '7471011203850001',
      'nip' => '198503122010011002',
      'jabatan' => 'Kepala Bidang Pelayanan',
      'tempat_lahir' => 'Kendari',
      'tanggal_lahir' => '1985-03-12',
    ],
  ];

  /**
   * Formulir yang siap dikirim: data pemohon, rincian layanan, surat, dan KTP untuk TTE.
   * `$tunggu` memajukan jam melewati FormPengajuan::DETIK_MINIMUM, seperti manusia yang mengisi formulir.
   */
  private function formulirLengkap(string $layanan, string $whatsapp = '6281234567890', bool $tunggu = true): Testable
  {
    $form = Livewire::test(FormPengajuan::class, ['layanan' => $layanan])
      ->set('instansi', 'Dinas Kesehatan Kota Kendari')
      ->set('nama_pemohon', 'Budi Santoso')
      ->set('whatsapp', $whatsapp)
      ->set('surat', $this->pdf());

    foreach (self::RINCIAN[$layanan] as $kunci => $nilai) {
      $form->set("rincian.{$kunci}", $nilai);
    }

    if ($layanan === 'pengajuan-tte') {
      $form->set('ktp', $this->pdf('ktp.pdf'));
    }

    if ($tunggu) {
      $this->travel(FormPengajuan::DETIK_MINIMUM)->seconds();
    }

    return $form;
  }

  public static function layananDaring(): array
  {
    return [['subdomain-hosting'], ['email-dinas'], ['pengajuan-tte']];
  }

  #[\PHPUnit\Framework\Attributes\DataProvider('layananDaring')]
  public function test_pengajuan_tersimpan_dengan_nomor_rincian_dan_berkas_di_disk_privat(string $layanan): void
  {
    $form = $this->formulirLengkap($layanan)->call('kirim')->assertHasNoErrors();

    $pengajuan = PengajuanLayanan::sole();
    $form->assertSet('terkirim', $pengajuan->nomor)->assertSee($pengajuan->nomor);

    $this->assertSame($layanan, $pengajuan->layanan);
    $this->assertSame('menunggu', $pengajuan->status);
    // Disimpan seragam supaya cek status cocok apa pun cara pemohon mengetiknya.
    $this->assertSame('081234567890', $pengajuan->whatsapp);
    $this->assertSame(self::RINCIAN[$layanan], $pengajuan->rincian);
    $this->assertMatchesRegularExpression('/^(DOM|EML|TTE)-\d{8}-[A-Z2-9]{4}$/', $pengajuan->nomor);
    $this->assertStringStartsWith('pengajuan/', $pengajuan->surat);
    Storage::disk('local')->assertExists($pengajuan->surat);
    Storage::disk('public')->assertDirectoryEmpty('/');

    if ($layanan === 'pengajuan-tte') {
      Storage::disk('local')->assertExists($pengajuan->ktp);
    } else {
      $this->assertNull($pengajuan->ktp);
    }
  }

  public function test_isian_formulir_mengikuti_layanan(): void
  {
    $this->get('/layanan/pengajuan-tte')
      ->assertOk()
      ->assertSee('id="ajukan"', false)
      ->assertSeeInOrder(['Nama lengkap', 'NIK', 'NIP', 'Tempat lahir', 'Tanggal lahir', 'Instansi', 'Jabatan', 'Nomor HP (WhatsApp)', 'KTP', 'Surat permohonan'])
      ->assertSee('Dipakai petugas Diskominfo untuk menghubungi Anda')
      ->assertSee('id="unggah-ktp"', false)
      ->assertSee('Isian bertanda')
      // Tidak ada lagi isian perangkap tersembunyi yang bisa terisi autofill peramban.
      ->assertDontSee('situs_web', false)
      ->assertSeeInOrder(['NIK', 'aria-hidden="true">*</span>', 'id="isian-rincian-nik"'], false)
      ->assertSeeInOrder(['id="unggah-ktp-label"', 'aria-hidden="true">*</span>', 'aria-required="true"'], false)
      // NIK 16 dan NIP 18 angka, nomor HP angka saja; huruf awal nama dkk. kapital di papan ketik ponsel.
      ->assertSeeInOrder(['id="isian-rincian-nik"', 'inputmode="numeric" pattern="[0-9]{16}" data-angka', 'minlength="16" maxlength="16"'], false)
      ->assertSeeInOrder(['id="isian-rincian-nip"', 'inputmode="numeric" pattern="[0-9]{18}" data-angka', 'minlength="18" maxlength="18"'], false)
      ->assertSeeInOrder(['id="isian-whatsapp"', 'inputmode="numeric" pattern="[0-9]*" data-angka', 'maxlength="15"'], false)
      ->assertSeeInOrder(['id="isian-rincian-jabatan"', 'autocapitalize="words"'], false)
      ->assertDontSee('Nama subdomain');

    $this->get('/layanan/email-dinas')
      ->assertOk()
      ->assertSeeInOrder(['Nama lengkap', 'NIP', 'Tempat lahir', 'Tanggal lahir', 'Instansi', 'Nomor HP (WhatsApp)', 'Surat permohonan'])
      ->assertSeeInOrder(['id="isian-rincian-nip"', 'inputmode="numeric" pattern="[0-9]{18}" data-angka', 'minlength="18" maxlength="18"'], false)
      ->assertSeeInOrder(['id="isian-whatsapp"', 'data-angka', 'maxlength="15"'], false)
      ->assertDontSee('id="isian-rincian-nik"', false)
      ->assertDontSee('id="unggah-ktp"', false);

    $this->get('/layanan/subdomain-hosting')
      ->assertOk()
      ->assertSeeInOrder(['Nama instansi/OPD', 'Nama subdomain', '.kendarikota.go.id', 'Jenis layanan', 'Layanan publik', 'Layanan administrasi pemerintahan', 'IP address tujuan', 'Lokasi hosting/server', 'Deskripsi fungsi aplikasi/website', 'Surat permohonan'])
      // IP tujuan satu-satunya isian opsional: tanpa tanda *.
      ->assertSeeInOrder(['IP address tujuan', '(opsional)', 'id="isian-rincian-ip_tujuan"'], false)
      ->assertSeeInOrder(['id="isian-rincian-deskripsi"', 'autocapitalize="sentences"'], false)
      ->assertSeeInOrder(['id="isian-whatsapp"', 'data-angka', 'maxlength="15"'], false)
      ->assertDontSee('id="isian-rincian-nip"', false)
      ->assertDontSee('id="unggah-ktp"', false);

    $this->get('/layanan/telpon-darurat-112')->assertOk()->assertDontSee('id="ajukan"', false);
  }

  public function test_isian_wajib_tiap_layanan_divalidasi(): void
  {
    Livewire::test(FormPengajuan::class, ['layanan' => 'pengajuan-tte'])
      ->call('kirim')
      ->assertHasErrors([
        'instansi' => 'required', 'nama_pemohon' => 'required', 'whatsapp' => 'required',
        'rincian.nik' => 'required', 'rincian.nip' => 'required', 'rincian.jabatan' => 'required',
        'rincian.tempat_lahir' => 'required', 'rincian.tanggal_lahir' => 'required',
        'ktp' => 'required', 'surat' => 'required',
      ]);

    Livewire::test(FormPengajuan::class, ['layanan' => 'email-dinas'])
      ->call('kirim')
      ->assertHasErrors(['rincian.nip' => 'required', 'rincian.tempat_lahir' => 'required', 'rincian.tanggal_lahir' => 'required'])
      ->assertHasNoErrors(['rincian.nik', 'rincian.jabatan', 'ktp']);

    // IP tujuan boleh kosong: server bisa saja disiapkan Diskominfo.
    Livewire::test(FormPengajuan::class, ['layanan' => 'subdomain-hosting'])
      ->call('kirim')
      ->assertHasErrors(['rincian.subdomain' => 'required', 'rincian.jenis_layanan' => 'required', 'rincian.lokasi_server' => 'required', 'rincian.deskripsi' => 'required'])
      ->assertHasNoErrors(['rincian.ip_tujuan', 'rincian.nip', 'ktp']);

    $this->assertSame(0, PengajuanLayanan::count());
  }

  public function test_nik_nip_dan_tanggal_lahir_divalidasi(): void
  {
    $this->formulirLengkap('pengajuan-tte')
      ->set('rincian.nik', '7471 0112')
      ->set('rincian.nip', '19850312201001100')
      ->set('rincian.tanggal_lahir', now()->addDay()->toDateString())
      ->call('kirim')
      ->assertHasErrors(['rincian.nik' => 'digits', 'rincian.nip' => 'digits', 'rincian.tanggal_lahir' => 'before']);

    $this->assertSame(0, PengajuanLayanan::count());
  }

  public function test_subdomain_dirapikan_dan_ip_divalidasi(): void
  {
    $this->formulirLengkap('subdomain-hosting')
      ->set('rincian.subdomain', ' Dinkes.kendarikota.go.id ')
      ->assertSet('rincian.subdomain', 'dinkes')
      ->set('rincian.ip_tujuan', '')
      ->call('kirim')
      ->assertHasNoErrors();

    $this->assertSame('dinkes', PengajuanLayanan::sole()->rincian['subdomain']);
    $this->assertNull(PengajuanLayanan::sole()->rincian['ip_tujuan']);

    $this->formulirLengkap('subdomain-hosting')
      ->set('rincian.subdomain', '-dinkes_baru')
      ->set('rincian.ip_tujuan', '300.1.1.1')
      ->set('rincian.jenis_layanan', 'lainnya')
      ->call('kirim')
      ->assertHasErrors(['rincian.subdomain' => 'regex', 'rincian.ip_tujuan' => 'ip', 'rincian.jenis_layanan' => 'in']);
  }

  public function test_nomor_hp_hanya_angka_dan_nomor_seluler(): void
  {
    // Spasi, tanda hubung, dan "+" ditolak (di peramban sudah dibuang saat diketik atau ditempel).
    foreach (['0812 3456 7890', '0812-3456-7890', '+6281234567890', 'nomor saya'] as $salah) {
      $this->formulirLengkap('email-dinas')->set('whatsapp', $salah)->call('kirim')->assertHasErrors(['whatsapp' => 'regex']);
    }

    // Angka saja tapi bukan nomor seluler.
    foreach (['12345', '02112345678'] as $salah) {
      $this->formulirLengkap('email-dinas')->set('whatsapp', $salah)->call('kirim')->assertHasErrors('whatsapp');
    }

    foreach (['081234567890', '6281234567890', '8123456789'] as $benar) {
      $this->formulirLengkap('email-dinas')->set('whatsapp', $benar)->assertHasNoErrors('whatsapp');
    }
  }

  public function test_nama_tempat_instansi_dan_jabatan_diawali_huruf_kapital(): void
  {
    // Dirapikan begitu isian ditinggalkan; singkatan dan kata tugas di tengah tetap seperti diketik.
    $this->formulirLengkap('pengajuan-tte')
      ->set('nama_pemohon', '  budi   santoso ')
      ->assertSet('nama_pemohon', 'Budi Santoso')
      ->set('instansi', 'dinas pemberdayaan perempuan dan perlindungan anak')
      ->set('rincian.tempat_lahir', 'kendari')
      ->set('rincian.jabatan', 'kepala UPTD puskesmas')
      ->call('kirim')
      ->assertHasNoErrors();

    $tte = PengajuanLayanan::sole();
    $this->assertSame('Budi Santoso', $tte->nama_pemohon);
    $this->assertSame('Dinas Pemberdayaan Perempuan dan Perlindungan Anak', $tte->instansi);
    $this->assertSame('Kendari', $tte->rincian['tempat_lahir']);
    $this->assertSame('Kepala UPTD Puskesmas', $tte->rincian['jabatan']);

    // Lokasi hosting dan deskripsi berupa kalimat: cukup huruf pertamanya.
    $this->formulirLengkap('subdomain-hosting', '089999999999')
      ->set('nama_pemohon', 'andi')
      ->set('instansi', 'dinas kesehatan')
      ->set('rincian.lokasi_server', 'pusat data diskominfo')
      ->set('rincian.deskripsi', "portal informasi.\nuntuk masyarakat")
      ->call('kirim')
      ->assertHasNoErrors();

    $domain = PengajuanLayanan::where('layanan', 'subdomain-hosting')->sole();
    $this->assertSame(['Andi', 'Dinas Kesehatan'], [$domain->nama_pemohon, $domain->instansi]);
    $this->assertSame('Pusat data diskominfo', $domain->rincian['lokasi_server']);
    $this->assertSame("Portal informasi.\nuntuk masyarakat", $domain->rincian['deskripsi']);
  }

  public function test_surat_dan_ktp_hanya_menerima_dokumen_atau_gambar(): void
  {
    $this->formulirLengkap('pengajuan-tte')
      ->set('surat', UploadedFile::fake()->createWithContent('surat.php', '<?php echo 1;'))
      ->set('ktp', UploadedFile::fake()->createWithContent('ktp.docx', 'bukan gambar'))
      ->call('kirim')
      ->assertHasErrors(['surat' => 'mimes', 'ktp' => 'mimes']);
  }

  public function test_kiriman_terlalu_cepat_dianggap_bot(): void
  {
    $form = $this->formulirLengkap('email-dinas', tunggu: false)->call('kirim')->assertHasErrors('surat');
    $this->assertSame(0, PengajuanLayanan::count());

    // Formulir yang sama tetap bisa dikirim setelah jeda wajar, tanpa muat ulang.
    $this->travel(FormPengajuan::DETIK_MINIMUM)->seconds();
    $form->call('kirim')->assertHasNoErrors();
    $this->assertSame(1, PengajuanLayanan::count());
  }

  public function test_foto_ktp_dikompres_jadi_webp_dan_surat_pdf_utuh(): void
  {
    $this->formulirLengkap('pengajuan-tte')
      ->set('ktp', UploadedFile::fake()->image('ktp.jpg', 3000, 2000))
      ->call('kirim')
      ->assertHasNoErrors();

    $pengajuan = PengajuanLayanan::sole();
    $this->assertStringEndsWith('.webp', $pengajuan->ktp);
    [$lebar] = getimagesizefromstring(Storage::disk('local')->get($pengajuan->ktp));
    $this->assertSame(config('app.upload.kompres_gambar.lebar_maks'), $lebar);

    $this->assertStringEndsWith('.pdf', $pengajuan->surat);
    $this->assertStringStartsWith('%PDF-1.4', Storage::disk('local')->get($pengajuan->surat));
  }

  public function test_pengajuan_dibatasi_per_jaringan(): void
  {
    for ($i = 0; $i < FormPengajuan::BATAS_PER_JAM; $i++) {
      $this->formulirLengkap('email-dinas')->call('kirim')->assertHasNoErrors();
    }

    $this->formulirLengkap('email-dinas')->call('kirim')->assertHasErrors('surat');
    $this->assertSame(FormPengajuan::BATAS_PER_JAM, PengajuanLayanan::count());
  }

  public function test_cek_status_cukup_nomor_hp_lalu_pilih_dari_daftar(): void
  {
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $this->formulirLengkap('email-dinas')->call('kirim');
    $this->formulirLengkap('subdomain-hosting', '089999999999')->call('kirim');
    [$tte, $email, $orangLain] = PengajuanLayanan::orderBy('id')->get();

    Livewire::test(CekPengajuan::class)
      ->set('whatsapp', '081111111111')
      ->call('cari')
      ->assertSee('Belum ada pengajuan dengan nomor ini')
      ->assertDontSee($tte->nomor);

    // Isian hanya angka; spasi, tanda hubung, dan "+" ditolak (di peramban sudah dibuang saat diketik).
    foreach (['0812-3456-7890', '+6281234567890', '0812 abc'] as $salah) {
      Livewire::test(CekPengajuan::class)->set('whatsapp', $salah)->call('cari')->assertHasErrors(['whatsapp' => 'regex'])->assertSet('dicari', null);
    }

    // Awalan 62 tetap cocok dengan 08 saat mengajukan; hanya pengajuan nomor itu yang tampil.
    $cek = Livewire::test(CekPengajuan::class)
      ->set('whatsapp', '6281234567890')
      ->call('cari')
      ->assertSee('2 pengajuan untuk nomor ini')
      ->assertSeeInOrder([$email->nomor, $tte->nomor])
      ->assertSee('aria-label="Lihat tahapan pengajuan ' . $tte->nomor . '"', false)
      // Tanggal dan jam pengajuan, bukan hanya tanggal.
      ->assertSee(Situs::tanggalJam($tte->created_at))
      ->assertDontSee($orangLain->nomor)
      ->assertDontSee('<dialog', false);

    // Tahapan dibuka di dialog; daftar ringkas tetap ada di belakangnya.
    $cek->call('pilih', $tte->nomor)
      ->assertSet('nomor', $tte->nomor)
      ->assertSeeHtml('<dialog')
      ->assertSeeHtml('wire:key="dialog-' . $tte->nomor . '"')
      ->assertSeeInOrder(['Pengajuan diterima', 'Diverifikasi petugas', 'Pengajuan disetujui'])
      ->assertSeeInOrder([$email->nomor, $tte->nomor])
      // Data pribadi dari formulir tidak ikut tampil di halaman publik.
      ->assertDontSee(self::RINCIAN['pengajuan-tte']['nik']);

    $cek->call('tutup')->assertSet('nomor', '')->assertDontSee('<dialog', false)->assertSee('2 pengajuan untuk nomor ini');

    // Menebak nomor pengajuan orang lain tidak membuka apa pun, dan nomor HP yang dicari tidak bisa ditukar dari peramban.
    $cek->call('pilih', $orangLain->nomor)->assertDontSee($orangLain->nomor)->assertDontSee('<dialog', false);
    $this->expectException(\Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException::class);
    $cek->set('dicari', $orangLain->whatsapp);
  }

  public function test_tanda_terima_langsung_membuka_pengajuannya_setelah_nomor_hp_diisi(): void
  {
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $this->formulirLengkap('email-dinas')->call('kirim');
    $tte = PengajuanLayanan::where('layanan', 'pengajuan-tte')->sole();

    Livewire::withQueryParams(['nomor' => strtolower($tte->nomor)])
      ->test(CekPengajuan::class)
      ->set('whatsapp', '081234567890')
      ->call('cari')
      ->assertSet('nomor', $tte->nomor)
      ->assertSee('2 pengajuan untuk nomor ini')
      ->assertSeeHtml('wire:key="dialog-' . $tte->nomor . '"');

    // Nomor di URL yang bukan milik nomor HP itu dibuang, jadi yang tampil daftar (kosong).
    Livewire::withQueryParams(['nomor' => $tte->nomor])
      ->test(CekPengajuan::class)
      ->set('whatsapp', '089999999999')
      ->call('cari')
      ->assertSet('nomor', '')
      ->assertDontSee($tte->nomor);

    // Placeholder membantu pemohon mengenali format; halaman pribadi tidak diindeks.
    $this->get('/layanan/cek-pengajuan')
      ->assertOk()
      ->assertSee('placeholder="Mis. 081234567890"', false)
      ->assertSee('inputmode="numeric" pattern="[0-9]*" data-angka', false)
      ->assertSee('maxlength="15"', false)
      ->assertDontSee('id="isian-nomor"', false)
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
      ->assertSee('dinkes.kendarikota.go.id')
      ->assertSee('Layanan publik')
      ->assertSee('103.150.12.34')
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
      ->set('whatsapp', $tolak->whatsapp)
      ->call('cari')
      ->call('pilih', $tolak->nomor)
      ->assertSee('Ditolak')
      ->assertSee('Surat belum ditandatangani.');
  }

  public function test_rincian_tte_tampil_untuk_admin(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $pengajuan = PengajuanLayanan::sole();

    Livewire::test(ViewPengajuanLayanan::class, ['record' => $pengajuan->getRouteKey()])
      ->assertSee('7471011203850001')
      ->assertSee('198503122010011002')
      ->assertSee('Kepala Bidang Pelayanan')
      ->assertSee('Kendari, 12 Maret 1985')
      ->assertSee(route('filament.admin.pengajuan-berkas', [$pengajuan, 'ktp']))
      ->assertDontSee('Subdomain');
  }

  public function test_surat_dan_ktp_pengajuan_hanya_untuk_admin(): void
  {
    $this->seed();
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $this->formulirLengkap('email-dinas')->call('kirim');
    [$tte, $email] = PengajuanLayanan::orderBy('id')->get();

    foreach (['surat', 'ktp'] as $berkas) {
      $this->get(route('filament.admin.pengajuan-berkas', [$tte, $berkas]))->assertRedirect('/admin/login');
      // Disk privat tidak bisa dibuka lewat /storage: rute `serve` disk local meminta tanda tangan URL.
      $this->get('/storage/' . $tte->{$berkas})->assertForbidden();
    }

    $this->actingAs(User::first());

    foreach (['surat', 'ktp'] as $berkas) {
      $this->get(route('filament.admin.pengajuan-berkas', [$tte, $berkas]))->assertOk()->assertHeader('Content-Type', 'application/pdf');
    }

    // Pengajuan tanpa KTP, dan kolom lain yang bukan berkas, tidak bisa dibuka lewat rute ini.
    $this->get(route('filament.admin.pengajuan-berkas', [$email, 'ktp']))->assertNotFound();
    $this->get("/admin/pengajuan-layanan/{$tte->id}/whatsapp")->assertNotFound();
  }

  public function test_menghapus_pengajuan_ikut_menghapus_surat_dan_ktp(): void
  {
    $this->formulirLengkap('pengajuan-tte')->call('kirim');
    $pengajuan = PengajuanLayanan::sole();

    $pengajuan->delete();

    Storage::disk('local')->assertMissing($pengajuan->surat);
    Storage::disk('local')->assertMissing($pengajuan->ktp);
  }

  /** Pengaturan Telegram dalam bentuk yang disimpan halaman Pengaturan Situs (token terenkripsi). */
  private function aturTelegram(bool $aktif): void
  {
    ProfilDinas::updateOrCreate(['jenis' => 'pengaturan'], ['konten' => [
      ['id' => 'telegram_aktif', 'value' => $aktif],
      ['id' => 'telegram_token', 'value' => Telegram::kunci('123456789:' . str_repeat('A', 35))],
      ['id' => 'telegram_chat_id', 'value' => '-1001234567890'],
    ]]);
  }

  public function test_pengajuan_baru_diberitahukan_ke_telegram_dengan_tombol_ke_panel(): void
  {
    config(['app.url' => 'https://diskominfo.kendarikota.go.id']);
    // defer() biasanya menunggu balasan terkirim; di tes langsung dijalankan.
    $this->withoutDefer();
    $this->aturTelegram(aktif: true);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true, 'result' => []])]);

    $this->formulirLengkap('subdomain-hosting')->call('kirim')->assertHasNoErrors();

    $pengajuan = PengajuanLayanan::sole();
    Http::assertSentCount(1);
    Http::assertSent(fn (Request $r) => $r->url() === 'https://api.telegram.org/bot123456789:' . str_repeat('A', 35) . '/sendMessage'
      && $r['chat_id'] === '-1001234567890'
      && $r['parse_mode'] === 'HTML'
      && str_contains($r['text'], '🌐 <b>Subdomain &amp; Hosting</b>')
      && str_contains($r['text'], "🔖 Nomor: <b>{$pengajuan->nomor}</b>")
      && str_contains($r['text'], '👤 Pemohon: <b>Budi Santoso</b>')
      && str_contains($r['text'], '🔗 Subdomain: <b>dinkes.kendarikota.go.id</b>')
      && str_contains($r['text'], '🏷️ Jenis layanan: <b>Layanan publik</b>')
      && str_contains($r['text'], '📎 Berkas: <b>surat permohonan</b>')
      && str_contains($r['text'], 'Perlu ditindak saat ini: <b>1 pengajuan</b>')
      // Data pribadi (nomor HP, NIK, NIP) tidak ikut ke Telegram.
      && ! str_contains($r['text'], '081234567890')
      && $r['reply_markup'] === ['inline_keyboard' => [[
        ['text' => '🔍 Periksa pengajuan', 'url' => "https://diskominfo.kendarikota.go.id/admin/pengajuan-layanan/{$pengajuan->id}"],
      ]]]);
  }

  public function test_tautan_panel_ditulis_sebagai_teks_bila_situs_belum_beralamat_publik(): void
  {
    config(['app.url' => 'http://localhost:8000']);
    $this->withoutDefer();
    $this->aturTelegram(aktif: true);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true, 'result' => []])]);

    $this->formulirLengkap('pengajuan-tte')->call('kirim')->assertHasNoErrors();

    $pengajuan = PengajuanLayanan::sole();
    Http::assertSent(fn (Request $r) => ! isset($r['reply_markup'])
      && str_contains($r['text'], '✍️ <b>Tanda Tangan Elektronik</b>')
      && str_contains($r['text'], '💼 Jabatan: <b>Kepala Bidang Pelayanan</b>')
      && str_contains($r['text'], '📎 Berkas: <b>surat permohonan dan KTP</b>')
      && ! str_contains($r['text'], '7471011203850001')
      && str_contains($r['text'], "<b>🔍 Periksa pengajuan: http://localhost:8000/admin/pengajuan-layanan/{$pengajuan->id}</b>"));
  }

  public function test_telegram_tidak_dihubungi_bila_notifikasi_dimatikan(): void
  {
    $this->withoutDefer();
    $this->aturTelegram(aktif: false);
    Http::fake();

    $this->formulirLengkap('email-dinas')->call('kirim')->assertHasNoErrors();

    $this->assertSame(1, PengajuanLayanan::count());
    Http::assertNothingSent();
  }

  public function test_telegram_gagal_tidak_menggagalkan_pengajuan(): void
  {
    $this->withoutDefer();
    $this->aturTelegram(aktif: true);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => false, 'description' => 'Bad Request: chat not found'], 400)]);

    $this->formulirLengkap('pengajuan-tte')->call('kirim')->assertHasNoErrors();

    $this->assertSame(1, PengajuanLayanan::count());
    Http::assertSentCount(1);
  }
}
