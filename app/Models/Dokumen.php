<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
  protected $table   = 'dokumen';
  protected $guarded = [];


  public function kategori()
  {
    return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
  }
}
