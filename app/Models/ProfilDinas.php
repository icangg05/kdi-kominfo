<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProfilDinas extends Model
{
  protected $table   = 'profil_dinas';
  protected $guarded = [];

  /**
   * `konten` menyimpan dua bentuk: HTML biasa, atau JSON (umumnya daftar [{id, value}, ...]).
   * Hanya jenis di bawah ini yang berbentuk JSON.
   */
  public const JENIS_DAFTAR = ['tagline-sambutan', 'misi', 'foto-diskominfo', 'fungsi', 'pengaturan', 'bagan-organisasi', 'sinkron-berita', 'sinkron-berita-atur'];

  /** Jenis yang kontennya path gambar di disk public (satu path, atau daftar [{id, value}]). */
  private const JENIS_GAMBAR = ['struktur-organisasi', 'foto-diskominfo'];

  // Gambar yang diganti atau dihapus dari form ikut dibuang dari disk. Dipasang di
  // updated, bukan updating, supaya file tidak hilang kalau query UPDATE-nya gagal.
  protected static function booted(): void
  {
    static::updated(function (ProfilDinas $baris) {
      if (in_array($baris->jenis, self::JENIS_GAMBAR, true)) {
        Storage::disk('public')->delete(array_diff(self::berkas($baris->getOriginal('konten')), self::berkas($baris->konten)));
      }
    });
  }

  /** @return array<int, string> */
  private static function berkas(mixed $konten): array
  {
    return collect(is_array($konten) ? $konten : [$konten])
      ->map(fn ($item) => is_array($item) ? ($item['value'] ?? null) : $item)
      ->filter()
      ->all();
  }

  public function getKontenAttribute(?string $value): mixed
  {
    if (! in_array($this->attributes['jenis'] ?? null, self::JENIS_DAFTAR, true)) {
      return $value;
    }

    $decoded = json_decode((string) $value, true);

    return is_array($decoded) ? $decoded : $value;
  }

  public function setKontenAttribute(mixed $value): void
  {
    $this->attributes['konten'] = is_array($value) ? json_encode($value) : $value;
  }

  public static function konten(string $jenis): mixed
  {
    return static::where('jenis', $jenis)->first()?->konten;
  }

  /** Daftar [{id, value}] disederhanakan jadi list nilai saja. */
  public static function nilai(string $jenis): array
  {
    return collect((array) static::konten($jenis))
      ->map(fn ($item) => is_array($item) ? ($item['value'] ?? null) : $item)
      ->filter()
      ->values()
      ->all();
  }

  /** Pengaturan global (telp, email, alamat, sosial media) sebagai map id => value. */
  public static function pengaturan(): array
  {
    return collect((array) static::konten('pengaturan'))->pluck('value', 'id')->all();
  }
}
