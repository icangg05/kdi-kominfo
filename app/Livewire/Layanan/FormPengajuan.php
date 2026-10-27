<?php

namespace App\Livewire\Layanan;

use App\Models\PengajuanLayanan;
use App\Support\KompresGambar;
use App\Support\NamaBerkas;
use App\Support\Telegram;
use App\Support\Teks;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

use function Illuminate\Support\defer;

/**
 * Formulir pengajuan layanan (subdomain, email dinas, TTE): data pemohon, isian khusus layanan
 * (`rincian`, lihat PengajuanLayanan::JENIS), surat permohonan, dan KTP untuk TTE.
 * Hasilnya masuk ke tabel pengajuan_layanan dengan status "menunggu" dan diverifikasi admin di panel;
 * admin diberi tahu lewat Telegram bila diaktifkan di Pengaturan Situs.
 */
class FormPengajuan extends Component
{
  use WithFileUploads;

  /** Pengajuan per IP per jam; cukup untuk OPD yang mengajukan beberapa layanan sekaligus. */
  public const BATAS_PER_JAM = 5;

  /**
   * Kiriman yang lebih cepat dari ini sejak formulir dimuat dianggap bot: manusia butuh waktu mengisi
   * data dan memilih berkas. Pengganti isian perangkap tersembunyi, yang ikut terisi autofill peramban
   * atau ekstensi pengisi formulir sehingga pemohon sungguhan tertolak.
   */
  public const DETIK_MINIMUM = 3;

  /** Isian nama, tempat, instansi, dan jabatan: huruf awal tiap kata dikapitalkan (Teks::kapitalTiapKata). */
  private const KAPITAL_TIAP_KATA = ['instansi', 'nama_pemohon', 'rincian.tempat_lahir', 'rincian.jabatan'];
  /** Isian berupa kalimat: cukup huruf pertamanya (Teks::kapitalAwal). */
  private const KAPITAL_AWAL = ['rincian.lokasi_server', 'rincian.deskripsi'];

  #[Locked]
  public string $layanan;

  public string $instansi = '';
  public string $nama_pemohon = '';
  public string $whatsapp = '';
  /** Isian khusus layanan; hanya kunci di PengajuanLayanan::JENIS[$layanan]['rincian'] yang dipakai. */
  public array $rincian = [];
  /** @var TemporaryUploadedFile|null */
  public $surat;
  /** @var TemporaryUploadedFile|null */
  public $ktp;
  /** Waktu formulir dimuat (Unix detik), untuk DETIK_MINIMUM. */
  #[Locked]
  public int $dimuat;

  /** Nomor pengajuan yang baru terkirim; selama terisi, formulir diganti tanda terima. */
  public ?string $terkirim = null;

  public function mount(string $layanan): void
  {
    abort_unless(isset(PengajuanLayanan::JENIS[$layanan]), 404);

    $this->layanan = $layanan;
    $this->rincian = array_fill_keys(PengajuanLayanan::JENIS[$layanan]['rincian'], '');
    $this->dimuat = now()->getTimestamp();
  }

  protected function rules(): array
  {
    $jenis = PengajuanLayanan::JENIS[$this->layanan];
    $rincian = [
      'nik' => ['required', 'digits:16'],
      'nip' => ['required', 'digits:18'],
      'jabatan' => ['required', 'string', 'max:255'],
      'tempat_lahir' => ['required', 'string', 'max:100'],
      'tanggal_lahir' => ['required', 'date_format:Y-m-d', 'before:today'],
      // Satu label DNS: huruf kecil, angka, dan tanda hubung yang tidak di awal atau akhir.
      'subdomain' => ['required', 'string', 'max:63', 'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/'],
      'jenis_layanan' => ['required', 'in:' . implode(',', array_keys(PengajuanLayanan::JENIS_LAYANAN_DOMAIN))],
      // Boleh kosong bila servernya belum ada, mis. hosting disiapkan Diskominfo.
      'ip_tujuan' => ['nullable', 'ip'],
      'lokasi_server' => ['required', 'string', 'max:255'],
      'deskripsi' => ['required', 'string', 'max:1000'],
    ];

    return [
      'instansi' => ['required', 'string', 'max:255'],
      'nama_pemohon' => ['required', 'string', 'max:255'],
      'whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', fn ($atribut, $nilai, $gagal) => preg_match(PengajuanLayanan::POLA_WHATSAPP, PengajuanLayanan::normalkanWhatsapp((string) $nilai))
        ?: $gagal('Nomor HP tidak valid. Gunakan nomor seluler aktif, mis. 0812 3456 7890.')],
      ...collect($jenis['rincian'])->mapWithKeys(fn ($kunci) => ["rincian.{$kunci}" => $rincian[$kunci]])->all(),
      'surat' => ['required', 'file', 'mimes:' . implode(',', PengajuanLayanan::BERKAS_SURAT), 'max:' . PengajuanLayanan::BERKAS_MAKS_KB],
      ...($jenis['ktp'] ? ['ktp' => ['required', 'file', 'mimes:' . implode(',', PengajuanLayanan::BERKAS_KTP), 'max:' . PengajuanLayanan::BERKAS_MAKS_KB]] : []),
    ];
  }

  protected function messages(): array
  {
    return [
      'whatsapp.regex' => 'Nomor HP hanya boleh berisi angka, mis. 081234567890.',
      'rincian.subdomain.regex' => 'Nama subdomain hanya boleh huruf kecil, angka, dan tanda hubung (tidak di awal atau akhir), mis. dinkes.',
      'rincian.tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
    ];
  }

  protected function validationAttributes(): array
  {
    return [
      'instansi' => $this->layanan === 'subdomain-hosting' ? 'nama instansi/OPD' : 'instansi',
      'nama_pemohon' => 'nama',
      'whatsapp' => 'nomor HP',
      ...collect(PengajuanLayanan::LABEL_RINCIAN)->mapWithKeys(fn ($label, $kunci) => ["rincian.{$kunci}" => $label])->all(),
      'surat' => 'surat permohonan',
      'ktp' => 'KTP',
    ];
  }

  /**
   * Galat per isian muncul begitu kontrolnya ditinggalkan, termasuk berkas yang baru dipilih. Teksnya dirapikan
   * dulu, jadi pemohon langsung melihat bentuk yang akan disimpan.
   */
  public function updated(string $properti): void
  {
    $this->rapikan($properti);

    if (array_key_exists($properti, $this->rules())) {
      $this->validateOnly($properti);
    }
  }

  private function rapikan(string $properti): void
  {
    $nilai = data_get($this, $properti);

    if (! is_string($nilai)) {
      return;
    }

    data_set($this, $properti, match (true) {
      // Pemohon sering mengetik nama lengkap atau huruf kapital; yang disimpan cukup label subdomainnya.
      $properti === 'rincian.subdomain' => Str::of($nilai)->trim()->lower()->chopEnd('.' . PengajuanLayanan::DOMAIN)->toString(),
      in_array($properti, self::KAPITAL_TIAP_KATA, true) => Teks::kapitalTiapKata($nilai),
      in_array($properti, self::KAPITAL_AWAL, true) => Teks::kapitalAwal($nilai),
      default => $nilai,
    });
  }

  public function kirim(): void
  {
    $kunci = 'pengajuan-layanan:' . request()->ip();

    if (RateLimiter::tooManyAttempts($kunci, self::BATAS_PER_JAM)) {
      $menit = (int) ceil(RateLimiter::availableIn($kunci) / 60);
      $this->addError('surat', "Terlalu banyak pengajuan dari jaringan Anda. Coba lagi dalam {$menit} menit.");

      return;
    }

    // Isian yang tidak sempat ditinggalkan (mis. langsung tekan Enter) belum melewati updated().
    foreach (['rincian.subdomain', ...self::KAPITAL_TIAP_KATA, ...self::KAPITAL_AWAL] as $properti) {
      $this->rapikan($properti);
    }

    try {
      $data = $this->validate();
    } catch (ValidationException $e) {
      // Isian bergalat pertama bisa jauh di atas tombol kirim; fokus ke sana supaya langsung terlihat.
      $this->js("setTimeout(() => document.querySelector('#ajukan [aria-invalid]')?.focus())");

      throw $e;
    }

    // Bot diberi tampilan yang sama dengan gagal validasi biasa, tanpa menyimpan apa pun.
    if (now()->getTimestamp() - $this->dimuat < self::DETIK_MINIMUM) {
      $this->addError('surat', 'Pengajuan tidak dapat diproses. Muat ulang halaman lalu coba lagi.');

      return;
    }

    RateLimiter::hit($kunci, 3600);

    $pengajuan = PengajuanLayanan::create([
      'layanan' => $this->layanan,
      'instansi' => $data['instansi'],
      'nama_pemohon' => $data['nama_pemohon'],
      'whatsapp' => PengajuanLayanan::normalkanWhatsapp($data['whatsapp']),
      'rincian' => array_map(fn ($nilai) => $nilai === '' ? null : $nilai, $data['rincian']),
      'surat' => $this->simpanBerkas($this->surat),
      'ktp' => $this->ktp ? $this->simpanBerkas($this->ktp) : null,
    ]);

    // Dikirim sesudah balasan sampai ke pemohon, jadi lambat atau gagalnya Telegram tidak menahan formulir.
    defer(fn () => Telegram::pengajuanBaru($pengajuan));

    $this->reset(['instansi', 'nama_pemohon', 'whatsapp', 'surat', 'ktp']);
    $this->rincian = array_fill_keys(array_keys($this->rincian), '');
    $this->terkirim = $pengajuan->nomor;
    $this->js("document.getElementById('ajukan')?.scrollIntoView({ block: 'start' })");
  }

  /** Foto KTP atau pindaian surat dikompres ke WebP; PDF dan Word disimpan apa adanya. */
  private function simpanBerkas(TemporaryUploadedFile $berkas): string
  {
    return KompresGambar::simpanWebp($berkas, Storage::disk(PengajuanLayanan::DISK), 'pengajuan')
      ?? $berkas->storeAs('pengajuan', NamaBerkas::acak($berkas->extension()), PengajuanLayanan::DISK);
  }

  public function ajukanLagi(): void
  {
    $this->terkirim = null;
  }

  public function render(): View
  {
    return view('livewire.layanan.form-pengajuan', ['jenis' => PengajuanLayanan::JENIS[$this->layanan]]);
  }
}
