<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dokumen extends Model
{
  protected $table   = 'dokumen';
  protected $guarded = [];


  public function kategori()
  {
    return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
  }

  /** Slug judul yang belum dipakai dokumen lain; judul kembar diberi akhiran -2, -3, dst. */
  public function slugUnik(): string
  {
    $dasar = Str::slug($this->judul) ?: 'dokumen';
    $slug = $dasar;

    for ($i = 2; static::where('slug', $slug)->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))->exists(); $i++) {
      $slug = "{$dasar}-{$i}";
    }

    return $slug;
  }

  protected static function booted()
  {
    // Slug dipakai di alamat unduhan dan nama berkas, jadi ikut berganti bila judul diubah.
    static::saving(function ($data) {
      if (! $data->slug || $data->isDirty('judul')) {
        $data->slug = $data->slugUnik();
      }
    });

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
