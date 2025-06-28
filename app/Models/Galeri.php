<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Galeri extends Model
{
  protected $table = 'galeri';
  protected $guarded = [];

  public $timestamps = false;

  protected static function booted()
  {
    static::updating(function ($galeri) {
      if ($galeri->isDirty('gambar')) {
        $gambarLama = $galeri->getOriginal('gambar');

        if ($gambarLama && Storage::disk('public')->exists($gambarLama)) {
          Storage::disk('public')->delete($gambarLama);
        }
      }
    });

    static::deleting(function ($galeri) {
      if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
        Storage::disk('public')->delete($galeri->gambar);
      }
    });
  }
}
