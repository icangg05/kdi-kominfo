<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
  protected $table = 'berita';
  protected $guarded = [];


  public function kategori()
  {
    return $this->belongsTo(KategoriBerita::class, 'kategori_berita_id');
  }
}
