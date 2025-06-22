<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
  protected $table = 'kategori_dokumen';
  protected $guarded = [];


  public function dokumen()
  {
    return $this->hasMany(Dokumen::class);
  }
}
