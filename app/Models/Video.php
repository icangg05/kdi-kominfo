<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
  protected $table = 'video';
  protected $guarded = [];

  public $timestamps = false;

  protected function casts(): array
  {
    return ['tanggal' => 'date'];
  }

  /** ID 11 karakter dari tautan YouTube (watch, youtu.be, embed, shorts, live), atau null bila bukan YouTube. */
  public function youtubeId(): ?string
  {
    preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([\w-]{11})~', (string) $this->tautan, $cocok);

    return $cocok[1] ?? null;
  }
}
