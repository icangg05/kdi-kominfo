<?php

namespace App\Models;

use App\Support\Situs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Pengajuan layanan (subdomain, email dinas, TTE) yang dikirim OPD lewat formulir di situs publik
 * dan diverifikasi admin di panel. Isian khusus tiap layanan disimpan di kolom JSON `rincian`.
 * Surat permohonan dan KTP (khusus TTE) ada di disk privat `local`.
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
  public const BERKAS_KTP = ['jpg', 'jpeg', 'png', 'pdf'];
  public const BERKAS_MAKS_KB = 10 * 1024;

  public const DOMAIN = 'kendarikota.go.id';

  /** Label isian `rincian`, dipakai untuk pesan galat formulir dan rincian di panel admin. */
  public const LABEL_RINCIAN = [
    'nik' => 'NIK',
    'nip' => 'NIP',
    'jabatan' => 'Jabatan',
    'tempat_lahir' => 'Tempat lahir',
    'tanggal_lahir' => 'Tanggal lahir',
    'subdomain' => 'Nama subdomain',
    'jenis_layanan' => 'Jenis layanan',
    'ip_tujuan' => 'IP address tujuan',
    'lokasi_server' => 'Lokasi hosting/server',
    'deskripsi' => 'Deskripsi fungsi aplikasi/website',
  ];

  /** Pilihan "jenis layanan" pada pengajuan subdomain, mengikuti pembagian layanan SPBE. */
  public const JENIS_LAYANAN_DOMAIN = [
    'publik' => 'Layanan publik',
    'administrasi' => 'Layanan administrasi pemerintahan',
  ];

  /** Nomor seluler Indonesia dalam bentuk seragam 08xxxxxxxxxx (8 sampai 13 digit sesudah 0). */
  public const POLA_WHATSAPP = '/^08[1-9][0-9]{6,11}$/';

  /**
   * Layanan yang bisa diajukan daring. `rincian` = kunci isian khusus layanan itu (LABEL_RINCIAN),
   * `ktp` = wajib unggah KTP, `isi_surat` memberi tahu pemohon apa yang perlu dimuat di surat.
   */
  public const JENIS = [
    'subdomain-hosting' => [
      'label' => 'Subdomain & Hosting',
      'kode' => 'DOM',
      'rincian' => ['subdomain', 'jenis_layanan', 'ip_tujuan', 'lokasi_server', 'deskripsi'],
      'ktp' => false,
      'isi_surat' => 'Surat ditandatangani pimpinan OPD dan menyebutkan subdomain yang dimohonkan.',
    ],
    'email-dinas' => [
      'label' => 'Email Dinas',
      'kode' => 'EML',
      'rincian' => ['nip', 'tempat_lahir', 'tanggal_lahir'],
      'ktp' => false,
      'isi_surat' => 'Surat ditandatangani pimpinan OPD.',
    ],
    'pengajuan-tte' => [
      'label' => 'Tanda Tangan Elektronik',
      'kode' => 'TTE',
      'rincian' => ['nik', 'nip', 'jabatan', 'tempat_lahir', 'tanggal_lahir'],
      'ktp' => true,
      'isi_surat' => 'Surat ditandatangani pimpinan OPD.',
    ],
  ];

  protected function casts(): array
  {
    return ['rincian' => 'array', 'diverifikasi_pada' => 'datetime'];
  }

  protected static function booted(): void
  {
    static::creating(function (PengajuanLayanan $pengajuan) {
      $pengajuan->nomor ??= static::nomorBaru($pengajuan->layanan);
    });

    static::deleting(function (PengajuanLayanan $pengajuan) {
      Storage::disk(self::DISK)->delete(array_filter([$pengajuan->surat, $pengajuan->ktp]));
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

  /** Mis. "Kendari, 12 Maret 1985"; null bila pengajuan ini tidak memuat TTL. */
  public function tempatTanggalLahir(): ?string
  {
    $tanggal = $this->rincian['tanggal_lahir'] ?? null;

    return $tanggal ? ($this->rincian['tempat_lahir'] ?? '') . ', ' . Situs::tanggal($tanggal) : null;
  }
}
