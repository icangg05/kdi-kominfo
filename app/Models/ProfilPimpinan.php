<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPimpinan extends Model
{
  protected $table   = 'profil_pimpinan';
  protected $guarded = [];


  public function getFotoAttribute(?string $value): mixed
  {
    $decoded = json_decode((string) $value, true);

    return is_array($decoded) ? $decoded : $value;
  }

  public function setFotoAttribute(mixed $value): void
  {
    $this->attributes['foto'] = is_array($value) ? json_encode(array_values($value)) : $value;
  }
}
