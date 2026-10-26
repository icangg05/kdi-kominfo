<?php

namespace App\Models;

use App\Support\Situs;
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

  public function ekstensi(): string
  {
    return pathinfo((string) $this->file, PATHINFO_EXTENSION);
  }

  /** Berkas tercatat dan masih ada di disk; bila tidak, tombol unduh dinonaktifkan. */
  public function adaBerkas(): bool
  {
    return $this->file && Storage::disk('public')->exists($this->file);
  }

  /** Alamat unduhan memakai slug judul supaya id dokumen tidak terlihat; null bila berkas hilang. */
  public function urlUnduh(): ?string
  {
    return $this->adaBerkas() ? "/download/{$this->slug}" : null;
  }

  /** Isi atribut data-dokumen: rincian yang sudah diformat, jadi skrip dialog cukup menyalin teks. */
  public function rincian(): string
  {
    $ada = $this->adaBerkas();

    return json_encode([
      'judul' => $this->judul,
      'kategori' => $this->kategori?->nama ?? '',
      'deskripsi' => $this->deskripsi,
      'ekstensi' => $this->ekstensi() ?: '–',
      'ukuran' => $ada ? Situs::ukuran(Storage::disk('public')->size($this->file)) : '–',
      'tanggal' => Situs::tanggal($this->created_at) ?: '–',
      'unduhan' => Situs::angka($this->total_unduhan) . ' kali',
      'unduh' => $ada ? "/download/{$this->slug}" : null,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
