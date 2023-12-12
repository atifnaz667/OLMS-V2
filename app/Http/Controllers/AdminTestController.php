<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Board;
use App\Models\Chapter;
use App\Models\Classes;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestChild;
use App\Models\Topic;
use App\Models\User;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminTestController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('test.admin-test.list');
  }
  /**
   * Display a listing of the resource.
   */
  public function fetchTestsRecords(Request $request)
  {
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:date,id'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      $perPage = $request->input('perPage', 10);
      $sort = $request->input('sort_by', 'id');
      $sort_order = $request->input('sort_order', 'asc');
      $table = $request->input('table', 'tests');
      $sorting = $table . '.' . $sort;

      $from = $request->input('from');
      $to = $request->input('to');
      $status = $request->input('status');

      $user_id = Auth::user()->id;
      $is_not_admin = Auth::user()->role_id == 1 ? 0 :1;
      $tests = Test::withCount('obtainedMarks')
      ->where('test_type','!=','Self')
        ->when($is_not_admin, function ($query)use($user_id)  {
          $query->where('created_by', $user_id);
        })
        ->when($from, function ($query) use ($from) {
          $query->where('created_at', '>', $from . " 00:00:00");
        })
        ->when($to, function ($query) use ($to) {
          $query->where('created_at', '<', $to . " 23:59:59");
        })
        ->when($status, function ($query) use ($status) {
          $query->where('status', $status);
        })
        ->orderBy($sorting, $sort_order)->paginate($perPage);
      $data = $tests->map(function ($tests) {

        if ($tests->expiry_date != null && $tests->expiry_date < date("Y-m-d")) {
          $status = '<span class="badge rounded bg-label-danger">Expired</span>';
        } elseif ($tests->status == 'Pending') {
          $status = '<span class="badge rounded bg-label-warning">' . $tests->status . '</span>';
        } elseif ($tests->status == 'Attempted') {
          $status = '<span class="badge rounded bg-label-success">' . $tests->status . '</span>';
        }
        return [
          'id' => $tests->id,
          'user' => Auth::user()->role_id == 4 ? $tests->createdBy->name : $tests->createdFor->name,
          'book' => $tests->book->name,
          'status' =>  $status,
          'status2' =>  $tests->status,
          'obtained_marks' => $tests->status == 'Attempted' ? $tests->obtained_marks_count : 0,
          'total_marks' => $tests->total_questions,
          'test_type' => $tests->test_type,
          'attempted_at' => Helpers::formatDate($tests->attempted_at),
          'test_date' => Helpers::formatDate($tests->test_date),
          'expiry_date' => Helpers::formatDate($tests->expiry_date),
          'created_at' => Helpers::formatDate($tests->created_at),
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Tests retrieved successfully',
        'data' => $data,
        'current_page' => $tests->currentPage(),
        'last_page' => $tests->lastPage(),
        'per_page' => $tests->perPage(),
        'total' => $tests->total(),
      ]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $boards = Board::orderBy('name', 'asc')->get();
    $classes = Classes::orderBy('name', 'asc')->get();
    $timeOptions = Helpers::getTimeForQuestions();
    return view('test.admin-test.add', ['timeOptions' => $timeOptions, 'boards' => $boards, 'classes' => $classes]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {

    $rules = array(
      'testDate' => 'required',
      'expiryDate' => 'required',
      'totalQuestions' => 'required|int|max:100',
      'chapters' => 'required',
      'book' => 'required',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->with(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      if ($request->expiryDate <= $request->testDate) {
        return back()->with(['status' => 'error', 'message' => 'Expiry date must be greater than test date'], 422);
      }
      DB::beginTransaction();
      $createdBy = Auth::user()->id;
      $testDate = $request->testDate;
      $expiryDate = $request->expiryDate;
      $totalQuestions = $request->totalQuestions ?? 10;
      $chapters = $request->chapters;
      $topics = $request->topics;
      $book = $request->book;
      $questionTime = $request->questionTime;
      $users = isset($request->students) ? $request->students : User::where([['board_id', $request->board], ['class_id', $request->class], ['status', 'Active']])->get()->pluck('id');
      if (count($users) == 0) {
        return back()->with(['status' => 'error', 'message' => 'Students not found'], 422);
      }
      foreach ($users as $user) {
        $storeTest = $this->storeTest($topics, $totalQuestions, $createdBy, $user, $expiryDate, $testDate, $questionTime, $book);
        if (!$storeTest) {
          DB::rollBack();
          return back()->with(['status' => 'error', 'message' => 'Questions not found against these chapters'], 422);
        }
      }
      DB::commit();
      return redirect('admin/test/list')->with(['status' => 'success', 'message' => 'Test created successfully'], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      $message = CustomErrorMessages::getCustomMessage($e);
      return back()->with(['status' => 'error', 'message' => $message], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }


  public function getBooksAjax(Request $req)
  {
    $rules = array(
      'board_id' => 'required|int|exists:boards,id',
      'class_id' => 'required|int|exists:classes,id'
    );
    $validator = Validator::make($req->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      $board_id = $req->board_id;
      $class_id = $req->class_id;
      $getBooks = Chapter::with('book')
        ->whereIn('id', function ($query) use ($board_id, $class_id) {
          $query->selectRaw('MIN(id)')
            ->from('chapters')
            ->where('board_id', $board_id)
            ->where('class_id', $class_id)
            ->groupBy('book_id');
        })
        ->get()
        ->pluck('book');

      $books = '<option value="">Select Book</option>';

      foreach ($getBooks as $book) {
        $books = $books . '<option value="' . $book->id . '">' . $book->name . '</option>';
      }
      return response()->json(['status' => 'success', 'books' => $books], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }

  public function getChaptersForTest(Request $req)
  {
    // old data

    // $chapters = Chapter::where([['book_id', $req->book_id], ['board_id', $req->board_id], ['class_id', $req->class_id]])->get();
    // $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
    //   ->select('topics.*')
    //   ->whereIn('topics.chapter_id', $chapters->pluck('id'))
    //   ->where('questions.question_type', '!=', 'mcq')
    //   ->distinct()
    //   ->get();
    // $cols = ' <div class="col-12 mb-2"> <input class="form-check-input " style="margin-right:1em" id="select-all" onclick="selectCheckboxes()" type="checkBox"> Select All</div>';
    // foreach ($chapters as $chapter) {
    //   $cols = $cols . ' <div class="col-sm-3 col-6 mb-2"> <input style="margin-right:1em" onclick="selectCheckbox()" type="checkBox" name="chapters[]" class="form-check-input checkboxes" value="' . $chapter->id . '"> ' . $chapter->name . '</div>';
    // }
    // if (count($chapters) == 0) {
    //   $cols = ' <div class="col-12"> <h6>No Chapters found against this book </h6></div>';
    // }
    // if (!$req->book_id) {
    //   $cols = ' <div class="col-12"> <h6>Please select book </h6></div>';
    // }
    // return $cols;

    $chapters = Chapter::whereHas('topics', function ($query) {
      $query
        ->join('questions', 'questions.topic_id', '=', 'topics.id')
        ->where('questions.question_type', '!=', 'mcq');
    })
      ->where('book_id', $req->book_id)
      ->where('board_id', $req->board_id)
      ->where('class_id', $req->class_id)
      ->get();

    $cols = '<div class="col-12 mb-2">
             <input class="form-check-input" style="margin-right:1em" id="select-all" onclick="selectCheckboxes()" type="checkBox"> Select All
         </div>';

    foreach ($chapters as $chapter) {
      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->where('topics.chapter_id', $chapter->id)
        ->where('questions.question_type', '!=', 'mcq')
        ->distinct()
        ->get();

      $cols .= '<div class="col-sm-12 mb-3">
                 <input style="margin-right:1em" onclick="selectCheckbox()" type="checkBox" name="chapters[]" class="form-check-input checkboxes" value="' . $chapter->id . '"> <strong>Unit: ' . $chapter->name . '</strong>
             </div>';

      $topicsInRow = 0;
      $cols .= '<div class="col-sm-12">';
      foreach ($topics as $topic) {
        if ($topicsInRow % 3 === 0 && $topicsInRow !== 0) {
          $cols .= '</div><div class="col-sm-12">';
        }
        $cols .= '<div class="col-sm-4 mb-2" style="display: inline-block; margin-right: 10px;">
                     <input style="margin-right:1em" type="checkBox" name="topics[]" class="form-check-input checkboxes" value="' . $topic->id . '"> ' . $topic->name . '
                 </div>';
        $topicsInRow++;
      }
      $cols .= '</div>';
    }

    if (
      count($chapters) == 0
    ) {
      $cols = '<div class="col-12"> <h6>No Chapters found against this book </h6></div>';
    }
    if (!$req->book_id) {
      $cols = '<div class="col-12"> <h6>Please select a book </h6></div>';
    }

    return $cols;


    $chapters = Chapter::whereHas('topics', function ($query) {
      $query
        ->join('questions', 'questions.topic_id', '=', 'topics.id')
        ->where('questions.question_type', '!=', 'mcq');
    })
      ->where('book_id', $req->book_id)
      ->where('board_id', $req->board_id)
      ->where('class_id', $req->class_id)
      ->get();
    $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
      ->select('topics.*')
      ->whereIn('topics.chapter_id', $chapters->pluck('id'))
      ->where('questions.question_type', '!=', 'mcq')
      ->distinct()
      ->get();
    $cols = '';
    $cols = '<div class="col-12 mb-2">
    <input class="form-check-input" style="margin-right: 1em" id="select-all" onclick="selectCheckboxes()" type="checkbox"> Select All
</div>';

    foreach ($chapters as $chapter) {
      $cols .= '<div class="mb-2 p-2">
        <div class="form-check">
            <input class="form-check-input chapter-checkbox" onclick="selectCheckbox()" type="checkbox" id="chapter_' . $chapter->id . '">
            <input type="hidden" id="book_id" value="' . $chapter->book_id . '">
            <h5 class="form-check-h5" for="chapter_' . $chapter->id . '">' . $chapter->name . '</h5>
        </div>
        <div id="topicList_' . $chapter->id . '" class="row mb-4">';

      foreach ($topics as $topic) {
        if ($topic->chapter_id === $chapter->id) {
          $cols .= '<div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input topic-checkbox" type="checkbox" id="topic_' . $topic->id . '">
                    <h6 class="form-check-h6" for="topic_' . $topic->id . '">' . $topic->name . '</h6>
                </div>
            </div>';
        }
      }

      $cols .= '</div></div>';
    }

    if (count($chapters) == 0) {
      $cols = '<div class="col-12"> <h6>No Chapters or Topics found against this book </h6></div>';
    }
    if (!$req->book_id) {
      $cols = '<div class="col-12"> <h6>Please select a book </h6></div>';
    }

    return $cols;
  }

  public function getStudentsForTest(Request $req)
  {

    $rules = array(
      'board_id' => 'required|int|exists:boards,id',
      'class_id' => 'required|int|exists:classes,id'
    );
    $validator = Validator::make($req->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      $getStudents = User::where('role_id',4)->where([['board_id', $req->board_id], ['class_id', $req->class_id], ['status', 'Active']])->orderBy('name')->get();

      $students = '<option value="">Select Students</option>';

      foreach ($getStudents as $student) {
        $students = $students . '<option value="' . $student->id . '">' . $student->name . '</option>';
      }
      return response()->json(['status' => 'success', 'students' => $students], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }

  public function storeTest($topics, $totalQuestions, $createdBy, $user, $expiryDate, $testDate, $questionTime, $book)
  {
    $student = User::find($user);
    // $topics = Topic::whereIn('chapter_id', $chapters)->get()->pluck('id');
    $questions = Question::inRandomOrder()->where([['question_type', 'mcq'],['test_id',null]])->whereIn('topic_id', $topics)->limit($totalQuestions)->get();
    if (!$questions){
      return false;
    }
    $test = new Test();
    $test->created_for = $student->id;
    $test->created_by = $createdBy;
    $test->status = 'Pending';
    $test->test_date = $testDate;
    $test->expiry_date = $expiryDate;
    $test->test_type = 'Monthly';
    $test->question_time = $questionTime;
    $test->total_questions = count($questions);
    $test->book_id = $book;
    $test->class_id = $student->class_id;
    $test->board_id = $student->board_id;
    $test->save();
    foreach ($questions as $question) {
      $testChild = new TestChild();
      $testChild->test_id = $test->id;
      $testChild->question_id = $question->id;
      $testChild->save();
    }
    return true;
  }
}
