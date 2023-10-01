<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignRole extends Model
{
  use HasFactory;
  protected $fillable = ['staff_id', 'board_id', 'class_id','subject_id'];

  public function staff()
  {
    return $this->belongsTo(User::class, 'staff_id');
  }
  public function book()
  {
    return $this->belongsTo(Book::class, 'subject_id');
  }

  public function board()
  {
    return $this->belongsTo(Board::class, 'board_id');
  }
  public function class()
  {
    return $this->belongsTo(Classes::class, 'class_id');
  }
}

               