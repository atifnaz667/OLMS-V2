<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Book extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'file'];

  public static function getBooksForParent($userId)
  {
    $user = User::find($userId);
    $board_id = $user->board_id;
    $class_id = $user->class_id;
    $books = Chapter::with('book')
      ->whereIn('id', function ($query) use ($board_id, $class_id) {
        $query
          ->selectRaw('MIN(id)')
          ->from('chapters')
          ->where('board_id', $board_id)
          ->where('class_id', $class_id)
          ->groupBy('book_id');
      })
      ->get()
      ->pluck('book');
    return $books;
  }
}
