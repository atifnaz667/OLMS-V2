<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignUser extends Model
{
  use HasFactory;

  public function child()
  {
    return $this->belongsTo(User::class, 'child_id');
  }
  public function parent()
  {
    return $this->belongsTo(User::class, 'parent_id');
  }
}
