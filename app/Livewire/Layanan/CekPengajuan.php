<?php

namespace App\Livewire\Layanan;

use App\Livewire\HalamanSitus;
use App\Models\PengajuanLayanan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Pemohon melihat status pengajuannya cukup dengan nomor HP yang dipakai saat mengajukan: semua pengajuan
 * dengan nomor itu tampil sebagai daftar ringkas, dan tahapan serta catatan petugas satu pengajuan dibuka di dialog.
 * Yang tampil di sini sengaja tidak memuat NIK, NIP, TTL, atau berkas.
 */
class CekPengajuan extends Component
{
  use HalamanSitus;

  public string $whatsapp = '';

  /**
   * Nomor HP (bentuk 08...) yang terakhir dicari. Dikunci supaya daftar hanya bisa diganti lewat cari(),
   * yang dibatasi per jaringan, bukan dengan mengubah properti dari peramban.
   */
  #[Locked]
  public ?string $dicari = null;

  /**
   * Pengajuan yang dialog tahapannya sedang terbuka. Dari tanda terima formulir (?nomor=...) atau tombol di daftar;
   * hanya dipakai bila termasuk pengajuan milik $dicari, jadi menebak nomor orang lain tidak membuka apa pun.
   */
  #[Url(except: '')]
  public string $nomor = '';

  protected function rules(): array
  {
    return ['whatsapp' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/']];
  }

  protected function messages(): array
  {
    return ['whatsapp.regex' => 'Nomor HP hanya boleh berisi angka, mis. 081234567890.'];
  }

  protected function validationAttributes(): array
  {
    return ['whatsapp' => 'nomor HP'];
  }

  public function cari(): void
  {
    $this->validate();

    $kunci = 'cek-pengajuan:' . request()->ip();

    if (RateLimiter::tooManyAttempts($kunci, 20)) {
      $this->addError('whatsapp', 'Terlalu banyak pencarian. Tunggu sebentar lalu coba lagi.');

      return;
    }

    RateLimiter::hit($kunci, 600);

    $this->dicari = PengajuanLayanan::normalkanWhatsapp($this->whatsapp);
    $this->nomor = strtoupper(trim($this->nomor));

    // Nomor bawaan tanda terima yang bukan milik nomor HP ini dibuang, supaya yang tampil daftarnya.
    if (! $this->daftar()->contains('nomor', $this->nomor)) {
      $this->nomor = '';
    }
  }

  public function pilih(string $nomor): void
  {
    $this->nomor = $nomor;
  }

  public function tutup(): void
  {
    $this->nomor = '';
  }

  private function daftar()
  {
    return $this->dicari
      ? PengajuanLayanan::where('whatsapp', $this->dicari)->latest('id')->get()
      : collect();
  }

  public function render(): View
  {
    $daftar = $this->daftar();

    return $this->halaman('livewire.layanan.cek-pengajuan', 'Cek Status Pengajuan', [
      'daftar' => $daftar,
      'pengajuan' => $this->nomor !== '' ? $daftar->firstWhere('nomor', $this->nomor) : null,
    ], [
      'deskripsi' => 'Lihat status pengajuan layanan subdomain, email dinas, dan TTE di Diskominfo Kota Kendari.',
      'noindex' => true,
    ]);
  }
}
