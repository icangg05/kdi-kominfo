<?php

namespace App\Livewire\Layanan;

use App\Models\PengajuanLayanan;
use App\Support\NamaBerkas;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 * Formulir pengajuan layanan (subdomain, email dinas, TTE): data singkat pemohon dan surat permohonan.
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
  public string $keterangan = '';
  /** @var TemporaryUploadedFile|null */
  public $surat;
  /** Perangkap bot: tak terlihat oleh manusia, jadi kiriman yang mengisinya diabaikan. */
  public string $situs_web = '';

  /** Nomor pengajuan yang baru terkirim; selama terisi, formulir diganti tanda terima. */
  public ?string $terkirim = null;

  public function mount(string $layanan): void
  {
    abort_unless(isset(PengajuanLayanan::JENIS[$layanan]), 404);

    $this->layanan = $layanan;
  }

  protected function rules(): array
  {
    return [
      'instansi' => ['required', 'string', 'max:255'],
      'nama_pemohon' => ['required', 'string', 'max:255'],
      'whatsapp' => ['required', 'string', 'max:20', fn ($atribut, $nilai, $gagal) => preg_match(PengajuanLayanan::POLA_WHATSAPP, PengajuanLayanan::normalkanWhatsapp((string) $nilai))
        ?: $gagal('Nomor WhatsApp tidak valid. Gunakan nomor seluler aktif, mis. 0812 3456 7890.')],
      'keterangan' => ['nullable', 'string', 'max:1000'],
      'surat' => ['required', 'file', 'mimes:' . implode(',', PengajuanLayanan::BERKAS_SURAT), 'max:' . PengajuanLayanan::BERKAS_MAKS_KB],
    ];
  }

  protected function validationAttributes(): array
  {
    return [
      'instansi' => 'perangkat daerah',
      'nama_pemohon' => 'nama pemohon',
      'whatsapp' => 'nomor WhatsApp',
      'surat' => 'surat permohonan',
    ];
  }

  /** Galat per isian muncul begitu kontrolnya ditinggalkan, termasuk berkas yang baru dipilih. */
  public function updated(string $properti): void
  {
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
      'keterangan' => $data['keterangan'] ?: null,
      'surat' => $this->surat->storeAs('pengajuan', NamaBerkas::acak($this->surat->extension()), PengajuanLayanan::DISK),
    ]);

    $this->reset(['instansi', 'nama_pemohon', 'whatsapp', 'keterangan', 'surat']);
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
