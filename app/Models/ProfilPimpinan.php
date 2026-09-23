<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProfilPimpinan extends Model
{
  protected $table   = 'profil_pimpinan';
  protected $guarded = [];


  public function pegawai(): BelongsTo
  {
    return $this->belongsTo(Pegawai::class);
  }

  /** Selalu daftar path. Data lama/seeder berbentuk [{id, value}] atau satu path biasa. */
  public function getFotoAttribute(?string $value): array
  {
    $decoded = json_decode((string) $value, true);

    return array_values(array_filter(array_map(
      fn ($f) => is_array($f) ? ($f['value'] ?? null) : $f,
      is_array($decoded) ? $decoded : [$value],
    )));
  }

  public function setFotoAttribute(mixed $value): void
  {
    $this->attributes['foto'] = is_array($value) ? json_encode(array_values($value)) : $value;
  }

  protected static function booted()
  {
    // FileUpload hanya melepas foto dari form; berkasnya dibuang di sini saat disimpan.
    static::updating(function ($data) {
      if ($data->isDirty('foto')) {
        Storage::disk('public')->delete(array_diff($data->getOriginal('foto'), $data->foto));
      }
    });
  }
}
