<?php

namespace App\Helpers;

use App\Models\Book;
use App\Models\Board;
use App\Models\Classes;
use App\Models\QuestionType;

class DropdownHelper
{
  public static function getBoardBookClass()
  {
    $books = Book::all();
    $boards = Board::all();
    $classes = Classes::all();
    $questionType = QuestionType::all();

    return ['Books' => $books, 'Boards' => $boards, 'Classes' => $classes, 'questionType' => $questionType];
  }
}
