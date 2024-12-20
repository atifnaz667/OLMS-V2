<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
  use HasFactory;
  protected $fillable = ['card_no', 'expiry_date', 'status', 'count','serial_no', 'valid_date'];
}
