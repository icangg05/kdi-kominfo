<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilDinas extends Model
{
  protected $table   = 'profil_dinas';
  protected $guarded = [];

  /**
   * `konten` menyimpan dua bentuk: HTML biasa, atau daftar JSON [{id, value}, ...].
   * Hanya jenis di bawah ini yang berbentuk daftar.
   */
  public const JENIS_DAFTAR = ['tagline-sambutan', 'misi', 'foto-diskominfo', 'fungsi', 'pengaturan'];

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

  /** Pengaturan global (telp, email, sosial media) sebagai map id => value. */
  public static function pengaturan(): array
  {
    return collect((array) static::konten('pengaturan'))->pluck('value', 'id')->all();
  }
}
