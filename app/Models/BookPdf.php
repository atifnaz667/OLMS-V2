<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPdf extends Model
{
    use HasFactory;
    protected $fillable = ['book_id', 'board_id', 'class_id','book_pdf'];

    public function book()
    {
      return $this->belongsTo(Book::class,'book_id');
    }
    public function board()
    {
      return $this->belongsTo(Board::class,'board_id');
    }
    public function class()
    {
      return $this->belongsTo(Classes::class,'class_id');
    }
}
