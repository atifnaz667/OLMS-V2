<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['teacher_id','student_id','book_id','comment'];

    public function teacher()
    {
      return $this->belongsTo(User::class, 'teacher_id');
    }
    public function book()
    {
      return $this->belongsTo(Book::class, 'book_id');
    }
}
