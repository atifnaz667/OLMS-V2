<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = ['name', 'email', 'password', 'card_id', 'last_activity_at', 'last_login_at'];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = ['password', 'remember_token'];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function role()
  {
    return $this->belongsTo(Role::class);
  }

  public function class()
  {
    return $this->belongsTo(Classes::class);
  }
  public function board()
  {
    return $this->belongsTo(Board::class);
  }
  public function assignUserAsParent()
  {
    return $this->hasOne(AssignUser::class, 'parent_id');
  }

  public function assignUserAsChild()
  {
    return $this->hasOne(AssignUser::class, 'child_id');
  }
}
