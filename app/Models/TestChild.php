<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestChild extends Model
{
    use HasFactory;

    public function question(){
      return $this->belongsTo(Question::class);
    }

    public function test(){
      return $this->belongsTo(Test::class);
    }

    public function selectedAnswer(){
      return $this->belongsTo(McqChoice::class,'mcq_choice_id');
    }
}
