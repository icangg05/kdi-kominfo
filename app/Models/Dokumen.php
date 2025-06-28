<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
  protected $table   = 'dokumen';
  protected $guarded = [];


  public function kategori()
  {
    return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
  }

  protected static function booted()
  {
    static::updating(function ($data) {
      if ($data->isDirty('file')) {
        $fileLama = $data->getOriginal('file');

        if ($fileLama && Storage::disk('public')->exists($fileLama)) {
          Storage::disk('public')->delete($fileLama);
        }
      }
    });

    static::deleting(function ($data) {
      if ($data->file && Storage::disk('public')->exists($data->file)) {
        Storage::disk('public')->delete($data->file);
      }
    });
  }
}
