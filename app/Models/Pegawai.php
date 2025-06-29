<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pegawai extends Model
{
  protected $table   = 'pegawai';
  protected $guarded = [];


  public function jabatan()
  {
    return $this->belongsTo(Jabatan::class);
  }


  protected static function booted()
  {
    static::updating(function ($data) {
      if ($data->isDirty('foto')) {
        $fotoLama = $data->getOriginal('foto');

        if ($fotoLama && Storage::disk('public')->exists($fotoLama)) {
          Storage::disk('public')->delete($fotoLama);
        }
      }
    });

    static::deleting(function ($data) {
      if ($data->foto && Storage::disk('public')->exists($data->foto)) {
        Storage::disk('public')->delete($data->foto);
      }
    });
  }
}
