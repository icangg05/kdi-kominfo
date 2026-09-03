<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
  protected $table   = 'visitor';
  protected $guarded = [];

  protected function casts(): array
  {
    return ['date' => 'date'];
  }


  public $timestamps = false;
}
