<?php

namespace App\Livewire\Layanan;

use App\Livewire\HalamanSitus;
use App\Models\PengajuanLayanan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Pemohon melihat status pengajuannya. Butuh nomor pengajuan dan nomor WhatsApp yang dipakai saat mengajukan,
 * supaya nomor pengajuan yang tertebak saja tidak cukup untuk membuka data orang lain.
 */
class CekPengajuan extends Component
{
  use HalamanSitus;

  /** Dibawa dari tanda terima formulir (?nomor=...); nomor WhatsApp sengaja tidak masuk URL. */
  #[Url(except: '')]
  public string $nomor = '';

  public string $whatsapp = '';

  public ?int $ditemukan = null;

  public bool $sudahCari = false;

  protected function rules(): array
  {
    return [
      'nomor' => ['required', 'string', 'max:32'],
      'whatsapp' => ['required', 'string', 'max:20'],
    ];
  }

  protected function validationAttributes(): array
  {
    return ['nomor' => 'nomor pengajuan', 'whatsapp' => 'nomor WhatsApp'];
  }

  public function cari(): void
  {
    $this->validate();

    $kunci = 'cek-pengajuan:' . request()->ip();

    if (RateLimiter::tooManyAttempts($kunci, 20)) {
      $this->addError('nomor', 'Terlalu banyak pencarian. Tunggu sebentar lalu coba lagi.');

      return;
    }

    RateLimiter::hit($kunci, 600);

    $this->nomor = strtoupper(trim($this->nomor));
    $this->ditemukan = PengajuanLayanan::where('nomor', $this->nomor)
      ->where('whatsapp', PengajuanLayanan::normalkanWhatsapp($this->whatsapp))
      ->value('id');
    $this->sudahCari = true;
  }

  public function render(): View
  {
    return $this->halaman('livewire.layanan.cek-pengajuan', 'Cek Status Pengajuan', [
      'pengajuan' => $this->ditemukan ? PengajuanLayanan::find($this->ditemukan) : null,
    ], [
      'deskripsi' => 'Lihat status pengajuan layanan subdomain, email dinas, dan TTE di Diskominfo Kota Kendari.',
      'noindex' => true,
    ]);
  }
}
