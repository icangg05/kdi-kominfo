<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilDinas extends Model
{
  protected $table   = 'profil_dinas';
  protected $guarded = [];


  /**
   * Akses konten agar bisa menampung teks biasa maupun array JSON.
   */
  public function getKontenAttribute($value)
  {
    $decoded = json_decode($value, true);

    // Kalau bisa di-decode jadi array, kembalikan array-nya
    return is_array($decoded) ? $decoded : $value;
  }

  /**
   * Setter agar array otomatis diubah jadi JSON saat disimpan.
   */
  public function setKontenAttribute($value)
  {
    // Kalau value array, encode ke JSON
    $this->attributes['konten'] = is_array($value) ? json_encode($value) : $value;
  }
}
