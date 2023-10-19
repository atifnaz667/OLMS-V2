<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTeacherStudent extends Model
{
    use HasFactory;
    protected $fillable = ['teacher_id','student_id', 'book_id'];

    // public function assignTeacher(){
    //     return $this->hasMany(User::class,'id','teacher_id');
    //   }
    public function student()
    {
      return $this->belongsTo(User::class, 'student_id');
    }
    public function teacher()
    {
      return $this->belongsTo(User::class, 'teacher_id');
    }
      public function book()
      {
        return $this->belongsTo(Book::class,'book_id');
      }
}
