<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqChoice extends Model
{
  use HasFactory;
  protected $fillable = [
    'question_id',
    'choice',
    'is_true',
    'reason'
  ];
  public function question()
  {
    return $this->belongsTo(Question::class, 'question_id');
  }
}
