<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementClasses extends Model
{
    use HasFactory;
    protected $table = 'announcement_classes';

    public function class(){
      return $this->belongsTo(Classes::class,'class_id');
    }

    public function board(){
      return $this->belongsTo(Board::class,'board_id');
    }
}
