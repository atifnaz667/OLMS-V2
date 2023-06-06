<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
  use HasFactory;
  protected $fillable = ['board_id', 'book_id', 'class_id', 'book_edition', 'chapter_no', 'name'];

  public function board()
  {
    return $this->belongsTo(Board::class);
  }

  public function book()
  {
    return $this->belongsTo(Book::class);
  }

  public function class()
  {
    return $this->belongsTo(Classes::class);
  }

  public function topics()
  {
    return $this->hasMany(Topic::class);
  }
}
