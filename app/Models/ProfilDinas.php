<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilDinas extends Model
{
  protected $table   = 'profil_dinas';
  protected $guarded = [];


  public function getKontenAttribute($value)
  {
    $decoded = json_decode($value, true);
    return is_array($decoded) ? $decoded : $value;
  }
}
