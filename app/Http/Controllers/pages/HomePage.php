<?php

namespace App\Http\Controllers\pages;

use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomePage extends Controller
{
  public function index()
  {
    $role_id = Auth::user()->role_id;
    $user_id = Auth::user()->id;
    $results = DropdownHelper::getBoardBookClass();
    $classes = $results['Classes'];
    $boards = $results['Boards'];
    if ($role_id == 1) {
      return view('dashboards.admin');
    } elseif ($role_id == 4) {
      if (Auth::user()->status == 'pending') {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-register-basic', [
          'pageConfigs' => $pageConfigs,
          'classes' => $classes,
          'boards' => $boards,
        ]);
      } else {
        $books = Book::getBooksForParent($user_id);
        $bookNames = json_encode($books->pluck('name'));
        return view('dashboards.student', [
          'bookNames' => $bookNames,
        ]);
      }
    } elseif ($role_id == 2) {
      if (Auth::user()->status == 'pending') {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-register-basic', [
          'pageConfigs' => $pageConfigs,
          'classes' => $classes,
          'boards' => $boards,
        ]);
      } else {
        $user_id = Auth::user()->assignUserAsParent->child_id;
        $books = Book::getBooksForParent($user_id);
        $bookNames = json_encode($books->pluck('name'));
        return view('dashboards.parent', [
          'bookNames' => $bookNames,
        ]);
      }
    } elseif ($role_id == 3) {
      return view('dashboards.teacher');
    }
  }

  public function getGraphDataAjax()
  {
    $user_id = Auth::user()->role_id == 4 ? Auth::user()->id : Auth::user()->assignUserAsParent->child_id;
    $books = Book::getBooksForParent($user_id);
    $resultsArray = [];
    $i = 0;
    foreach ($books as $book) {
      $test = Test::where([
        ['book_id', $book->id],
        ['created_for', $user_id],
        ['status', 'Attempted'],
        ['test_type', '!=', 'Self'],
      ])
        ->select(DB::raw('sum(total_questions) as total_marks'), DB::raw('sum(obtained_marks) as obtained_marks'))
        ->first();

      if ($test->total_marks != null) {
        $percentage = ($test->obtained_marks * 100) / $test->total_marks;
      } else {
        $percentage = 0;
      }
      $resultsArray['chart_data'][$i] = $percentage;
      $i++;
    }
    $highestValue = max($resultsArray['chart_data']);
    $activeOption = array_search($highestValue, $resultsArray['chart_data']);
    $resultsArray['active_option'] = $activeOption;
    return response()->json($resultsArray);
  }
}
