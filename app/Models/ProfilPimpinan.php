<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPimpinan extends Model
{
  protected $table   = 'profil_pimpinan';
  protected $guarded = [];


  public function getFotoAttribute($value)
  {
    $decoded = json_decode($value, true);
    return is_array($decoded) ? $decoded : $value;
  }
}
