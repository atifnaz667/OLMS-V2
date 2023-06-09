<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  use HasFactory;
  protected $fillable = [
    'topic_id',
    'question_type',
    'description'
  ];

  public function topic()
  {
    return $this->belongsTo(Topic::class);
  }

  public function scopeMcq($query)
  {
    return $query->where('question_type', 'mcq');
  }
  public function answer()
  {
    return $this->hasOne(SlAnswer::class);
  }
  public function mcq()
  {
    return $this->hasMany(McqChoice::class);
  }
}
