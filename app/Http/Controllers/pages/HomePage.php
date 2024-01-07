<?php

namespace App\Http\Controllers\pages;

use Illuminate\Http\Request;
use App\Helpers\DropDownHelper;
use App\Http\Controllers\Controller;
use App\Models\AssignTeacherStudent;
use App\Models\Book;
use App\Models\BookPdf;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomePage extends Controller
{
  public function index()
  {
    $role_id = Auth::user()->role_id;
    $user_id = Auth::user()->id;
    $results = DropDownHelper::getBoardBookClass();
    $classes = $results['Classes'];
    $boards = $results['Boards'];
    if ($role_id == 1 || $role_id == 5) {
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
        $today = date("Y-m-d H:i:s");
        $testCount = Test::
        where('test_type', '!=', 'Self')
        ->where('created_for', $user_id)
        ->where('expiry_date', '>=', now()) // Use <= for less than or equal to
        ->where('status', 'Pending')
        ->count();
        return view('dashboards.student', [
          'bookNames' => $bookNames,
          'testCount' => $testCount,
          // 'testCount' => 0,
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

  public function getGraphDataAjax(Request $req)
  {
    $user_id = Auth::user()->role_id == 4 ? Auth::user()->id : Auth::user()->assignUserAsParent->child_id;
    $books = Book::getBooksForParent($user_id);
    $resultsArray = [];
    $i = 0;
    $to_date = $req->to_date;
    $from_date = $req->from_date;
    foreach ($books as $book) {
      $test = Test::where([
        ['book_id', $book->id],
        ['created_for', $user_id],
        ['status', 'Attempted'],
        ['test_type', '!=', 'Self'],
      ])
        ->when($from_date, function ($q) use ($from_date) {
          $q->whereDate('created_at', '>=', $from_date);
        })
        ->when($to_date, function ($q) use ($to_date) {
          $q->whereDate('created_at', '<=', $to_date);
        })
        ->select(DB::raw('sum(total_questions) as total_marks'), DB::raw('sum(obtained_marks) as obtained_marks'))
        ->first();

      if ($test->total_marks != null) {
        $percentage = ($test->obtained_marks * 100) / $test->total_marks;
      } else {
        $percentage = 0;
      }
      $resultsArray['chart_data'][$i] = round($percentage);
      $i++;
    }
    $highestValue = max($resultsArray['chart_data']);
    $activeOption = array_search($highestValue, $resultsArray['chart_data']);
    $resultsArray['active_option'] = $activeOption;
    return response()->json($resultsArray);
  }
}
