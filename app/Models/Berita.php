<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Berita extends Model
{
  protected $table = 'berita';
  protected $guarded = [];


  public function kategori()
  {
    return $this->belongsTo(KategoriBerita::class, 'kategori_berita_id');
  }

  protected static function booted()
  {
    static::updating(function ($data) {
      if ($data->isDirty('thumbnail')) {
        $thumbnailLama = $data->getOriginal('thumbnail');

        if ($thumbnailLama && Storage::disk('public')->exists($thumbnailLama)) {
          Storage::disk('public')->delete($thumbnailLama);
        }
      }
    });

    static::deleting(function ($data) {
      if ($data->thumbnail && Storage::disk('public')->exists($data->thumbnail)) {
        Storage::disk('public')->delete($data->thumbnail);
      }
    });
  }
}
