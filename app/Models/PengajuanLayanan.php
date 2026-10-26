<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Pengajuan layanan (subdomain, email dinas, TTE) yang dikirim OPD lewat formulir di situs publik
 * dan diverifikasi admin di panel. Formulirnya sengaja minimal: rincian kebutuhan ada di surat
 * permohonan, dan tidak ada data pribadi seperti KTP/NPWP yang diunggah. Suratnya di disk privat `local`.
 */
class PengajuanLayanan extends Model
{
  protected $table = 'pengajuan_layanan';
  protected $guarded = [];

  public const DISK = 'local';

  public const STATUS = [
    'menunggu' => 'Menunggu verifikasi',
    'diproses' => 'Sedang diproses',
    'disetujui' => 'Disetujui',
    'ditolak' => 'Ditolak',
  ];

  /** Jenis berkas surat yang diterima, untuk aturan `mimes` dan atribut `accept` di formulir. */
  public const BERKAS_SURAT = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
  public const BERKAS_MAKS_KB = 10 * 1024;

  /** Nomor seluler Indonesia dalam bentuk seragam 08xxxxxxxxxx (8 sampai 13 digit sesudah 0). */
  public const POLA_WHATSAPP = '/^08[1-9][0-9]{6,11}$/';

  /**
   * Layanan yang bisa diajukan daring. `isi_surat` memberi tahu pemohon apa yang perlu dimuat di surat,
   * `contoh_keterangan` mengisi placeholder keterangan tambahan.
   */
  public const JENIS = [
    'subdomain-hosting' => [
      'label' => 'Subdomain & Hosting',
      'kode' => 'DOM',
      'isi_surat' => 'Surat ditandatangani pimpinan OPD dan memuat usulan nama subdomain, tujuan penggunaan, serta kontak teknis.',
      'contoh_keterangan' => 'Mis. usulan dinkes.kendarikota.go.id, butuh hosting WordPress',
    ],
    'email-dinas' => [
      'label' => 'Email Dinas',
      'kode' => 'EML',
      'isi_surat' => 'Surat ditandatangani pimpinan OPD dan memuat daftar nama, jabatan, serta usulan alamat email.',
      'contoh_keterangan' => 'Mis. 3 akun untuk sekretariat dan bidang',
    ],
    'pengajuan-tte' => [
      'label' => 'Tanda Tangan Elektronik',
      'kode' => 'TTE',
      'isi_surat' => 'Surat ditandatangani pimpinan OPD dan memuat nama, NIP, dan jabatan pejabat yang diajukan.',
      'contoh_keterangan' => 'Mis. untuk penandatanganan surat keluar dan SK',
    ],
  ];

  protected function casts(): array
  {
    return ['diverifikasi_pada' => 'datetime'];
  }

  protected static function booted(): void
  {
    static::creating(function (PengajuanLayanan $pengajuan) {
      $pengajuan->nomor ??= static::nomorBaru($pengajuan->layanan);
    });

    static::deleting(function (PengajuanLayanan $pengajuan) {
      Storage::disk(self::DISK)->delete($pengajuan->surat);
    });
  }

  public function verifikator()
  {
    return $this->belongsTo(User::class, 'diverifikasi_oleh');
  }

  /** Kode layanan + tanggal + 4 karakter acak tanpa huruf/angka yang mirip (O/0, I/1). */
  public static function nomorBaru(string $layanan): string
  {
    $kode = self::JENIS[$layanan]['kode'] ?? 'LYN';

    do {
      $acak = collect(range(1, 4))->map(fn () => Str::charAt('ABCDEFGHJKLMNPQRSTUVWXYZ23456789', random_int(0, 31)))->implode('');
      $nomor = "{$kode}-" . now()->format('Ymd') . "-{$acak}";
    } while (static::where('nomor', $nomor)->exists());

    return $nomor;
  }

  /** "0812-3456 7890", "+62 812..." dan "62812..." jadi "081234567890". */
  public static function normalkanWhatsapp(string $nomor): string
  {
    $angka = preg_replace('/\D/', '', $nomor);

    return match (true) {
      str_starts_with($angka, '62') => '0' . substr($angka, 2),
      str_starts_with($angka, '8') => '0' . $angka,
      default => $angka,
    };
  }

  /** Tautan wa.me memakai kode negara: 0812... jadi 62812... */
  public function tautanWhatsapp(): string
  {
    return 'https://wa.me/62' . substr($this->whatsapp, 1);
  }

  public function labelLayanan(): string
  {
    return self::JENIS[$this->layanan]['label'] ?? $this->layanan;
  }

  public function labelStatus(): string
  {
    return self::STATUS[$this->status] ?? $this->status;
  }
}
