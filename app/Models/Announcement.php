<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    public function postedBy(){
      return $this->belongsTo(User::class,'posted_by');
    }

    public function announcementClasses(){
      return $this->hasMany(AnnouncementClasses::class,'announcement_id');
    }

    public function board(){
      return $this->belongsTo(Board::class);
    }


}
