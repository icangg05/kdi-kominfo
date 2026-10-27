<?php

namespace App\Livewire\Layanan;

use App\Models\PengajuanLayanan;
use App\Support\NamaBerkas;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 * Formulir pengajuan layanan (subdomain, email dinas, TTE): data pemohon, isian khusus layanan
 * (`rincian`, lihat PengajuanLayanan::JENIS), surat permohonan, dan KTP untuk TTE.
 * Hasilnya masuk ke tabel pengajuan_layanan dengan status "menunggu" dan diverifikasi admin di panel.
 */
class FormPengajuan extends Component
{
  use WithFileUploads;

  /** Pengajuan per IP per jam; cukup untuk OPD yang mengajukan beberapa layanan sekaligus. */
  public const BATAS_PER_JAM = 5;

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
  /** Perangkap bot: tak terlihat oleh manusia, jadi kiriman yang mengisinya diabaikan. */
  public string $situs_web = '';

  /** Nomor pengajuan yang baru terkirim; selama terisi, formulir diganti tanda terima. */
  public ?string $terkirim = null;

  public function mount(string $layanan): void
  {
    abort_unless(isset(PengajuanLayanan::JENIS[$layanan]), 404);

    $this->layanan = $layanan;
    $this->rincian = array_fill_keys(PengajuanLayanan::JENIS[$layanan]['rincian'], '');
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
      'whatsapp' => ['required', 'string', 'max:20', fn ($atribut, $nilai, $gagal) => preg_match(PengajuanLayanan::POLA_WHATSAPP, PengajuanLayanan::normalkanWhatsapp((string) $nilai))
        ?: $gagal('Nomor HP tidak valid. Gunakan nomor seluler aktif, mis. 0812 3456 7890.')],
      ...collect($jenis['rincian'])->mapWithKeys(fn ($kunci) => ["rincian.{$kunci}" => $rincian[$kunci]])->all(),
      'surat' => ['required', 'file', 'mimes:' . implode(',', PengajuanLayanan::BERKAS_SURAT), 'max:' . PengajuanLayanan::BERKAS_MAKS_KB],
      ...($jenis['ktp'] ? ['ktp' => ['required', 'file', 'mimes:' . implode(',', PengajuanLayanan::BERKAS_KTP), 'max:' . PengajuanLayanan::BERKAS_MAKS_KB]] : []),
    ];
  }

  protected function messages(): array
  {
    return [
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

  /** Galat per isian muncul begitu kontrolnya ditinggalkan, termasuk berkas yang baru dipilih. */
  public function updated(string $properti): void
  {
    // Pemohon sering mengetik nama lengkap atau huruf kapital; yang disimpan cukup label subdomainnya.
    if ($properti === 'rincian.subdomain') {
      $this->rincian['subdomain'] = Str::of($this->rincian['subdomain'])->trim()->lower()->chopEnd('.' . PengajuanLayanan::DOMAIN)->toString();
    }

    if (array_key_exists($properti, $this->rules())) {
      $this->validateOnly($properti);
    }
  }

  public function kirim(): void
  {
    // Bot yang mengisi perangkap diberi tampilan yang sama dengan gagal validasi biasa, tanpa menyimpan apa pun.
    if ($this->situs_web !== '') {
      $this->addError('surat', 'Pengajuan tidak dapat diproses. Muat ulang halaman lalu coba lagi.');

      return;
    }

    $kunci = 'pengajuan-layanan:' . request()->ip();

    if (RateLimiter::tooManyAttempts($kunci, self::BATAS_PER_JAM)) {
      $menit = (int) ceil(RateLimiter::availableIn($kunci) / 60);
      $this->addError('surat', "Terlalu banyak pengajuan dari jaringan Anda. Coba lagi dalam {$menit} menit.");

      return;
    }

    try {
      $data = $this->validate();
    } catch (ValidationException $e) {
      // Isian bergalat pertama bisa jauh di atas tombol kirim; fokus ke sana supaya langsung terlihat.
      $this->js("setTimeout(() => document.querySelector('#ajukan [aria-invalid]')?.focus())");

      throw $e;
    }

    RateLimiter::hit($kunci, 3600);

    $pengajuan = PengajuanLayanan::create([
      'layanan' => $this->layanan,
      'instansi' => $data['instansi'],
      'nama_pemohon' => $data['nama_pemohon'],
      'whatsapp' => PengajuanLayanan::normalkanWhatsapp($data['whatsapp']),
      'rincian' => array_map(fn ($nilai) => $nilai === '' ? null : $nilai, $data['rincian']),
      'surat' => $this->surat->storeAs('pengajuan', NamaBerkas::acak($this->surat->extension()), PengajuanLayanan::DISK),
      'ktp' => $this->ktp?->storeAs('pengajuan', NamaBerkas::acak($this->ktp->extension()), PengajuanLayanan::DISK),
    ]);

    $this->reset(['instansi', 'nama_pemohon', 'whatsapp', 'surat', 'ktp']);
    $this->rincian = array_fill_keys(array_keys($this->rincian), '');
    $this->terkirim = $pengajuan->nomor;
    $this->js("document.getElementById('ajukan')?.scrollIntoView({ block: 'start' })");
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
