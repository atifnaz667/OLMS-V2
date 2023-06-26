<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    public function createdBy(){
      return $this->belongsTo(User::class,'created_by');
    }

    public function createdFor(){
      return $this->belongsTo(User::class,'created_for');
    }

    public function book(){
      return $this->belongsTo(Book::class);
    }

    public function testChildren(){
      return $this->hasMany(TestChild::class);
    }

    public function childToAttempt(){
      return $this->hasOne(TestChild::class)->where('is_viewed',0);
    }
}
