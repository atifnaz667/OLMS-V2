<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestChild;
use App\Models\Topic;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SelfAssessmentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $user_id = Auth::user()->id;
    $books =  Book::getBooksForParent($user_id);
    $timeOptions = Helpers::getTimeForQuestions();
    return view('test.self-test.add', ['timeOptions' => $timeOptions, 'books' => $books]);
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

  public function getChaptersForTest(Request $req)
  {
    $user = Auth::user();
    // old query
    // $chapters = Chapter::where([['book_id', $req->bookId], ['board_id', $user->board_id], ['class_id', $user->class_id]])->get();
    // $cols = ' <div class="col-12 mb-2"> <input class="form-check-input " style="margin-right:1em" id="select-all" onclick="selectCheckboxes()" type="checkBox"> Select All</div>';
    // foreach ($chapters as $chapter) {
    //   $cols = $cols . ' <div class="col-sm-3 col-12 mb-2"> <input style="margin-right:1em" onclick="selectCheckbox()" type="checkBox" name="chapters[]" class="form-check-input checkboxes" value="' . $chapter->id . '"> ' . $chapter->name . '</div>';
    // }
    // if (count($chapters) == 0) {
    //   $cols = ' <div class="col-12"> <h6>No Chapters found against this book </h6></div>';
    // }
    // if (!$req->bookId) {
    //   $cols = ' <div class="col-12"> <h6>Please select book </h6></div>';
    // }
    // return $cols;

    $chapters = Chapter::whereHas('topics', function ($query) {
      $query
        ->join('questions', 'questions.topic_id', '=', 'topics.id')
        ->where('questions.question_type', '=', 'mcq');
    })
      ->where('book_id', $req->bookId)
      ->where('board_id', $user->board_id)
      ->where('class_id', $user->class_id)
      ->get();

    $cols = '<div class="col-12 mb-2">
             <input class="form-check-input" style="margin-right:1em" id="select-all" onclick="selectCheckboxes()" type="checkBox"> Select All
         </div>';

    foreach ($chapters as $chapter) {
      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->where('topics.chapter_id', $chapter->id)
        ->where('questions.question_type', '=', 'mcq')
        ->distinct()
        ->get();

      $cols .= '<div class="col-sm-12 mb-3">
                 <input style="margin-right:1em" onclick="selectCheckbox()" type="checkBox" hidden name="chapters[]" class="form-check-input checkboxes" value="' . $chapter->id . '"> <strong>Unit: ' . $chapter->name . '</strong>
             </div>';

      $topicsInRow = 0;
      $cols .= '<div class="col-sm-12">';
      foreach ($topics as $topic) {
        if ($topicsInRow % 3 === 0 && $topicsInRow !== 0) {
          $cols .= '</div><div class="col-sm-12">';
        }
        $cols .= '<div class="col-sm-4 mb-2" style="display: inline-block; margin-right: 10px;">
                     <input style="margin-right:1em"  onclick="selectCheckbox()" type="checkBox" name="topics[]" class="form-check-input checkboxes" value="' . $topic->id . '"> ' . $topic->name . '
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
    if (!$req->bookId) {
      $cols = '<div class="col-12"> <h6>Please select a book </h6></div>';
    }

    return $cols;
  }

  public function store(Request $request)
  {
    $rules = array(
      'totalQuestions' => 'required|int|max:100',
      'chapters' => 'required',
      'book' => 'required',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->with(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      DB::beginTransaction();
      $createdBy = Auth::user()->id;
      $testDate = $request->testDate ?? Date('Y-m-d');
      $totalQuestions = $request->totalQuestions ?? 10;
      $chapters = $request->chapters;
      $topics = $request->topics;
      $book = $request->book;
      $questionTime = $request->questionTime;
      $oldAssessment = Test::where([['created_by', $createdBy], ['test_type', 'self']])->first();
      if ($oldAssessment) {
        TestChild::where('test_id', $oldAssessment->id)->delete();
        Test::find($oldAssessment->id)->delete();
      }
      $storeTest = $this->storeTest($topics, $totalQuestions, $createdBy, $createdBy, $testDate, $questionTime, $book);
      if (!$storeTest) {
        DB::rollBack();
        return back()->with(['status' => 'error', 'message' => 'Questions not found against these chapters'], 422);
      }

      DB::commit();
      return back()->with(['status' => 'success', 'message' => 'Test created successfully', 'test_id' => $storeTest], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      $message = CustomErrorMessages::getCustomMessage($e);
      return back()->with(['status' => 'error', 'message' => $message], 500);
    }
  }
  public function storeTest($topics, $totalQuestions, $createdBy, $createdFor, $testDate, $questionTime, $book)
  {
    // $topics = Topic::whereIn('chapter_id', $chapters)->get()->pluck('id');
    $questions = Question::inRandomOrder()->where([['question_type', 'mcq'],['test_id',null]])->whereIn('topic_id', $topics)->limit($totalQuestions)->get();
    if (count($questions) == 0) {
      return false;
    }
    $test = new Test();
    $test->created_for = $createdFor;
    $test->created_by = $createdBy;
    $test->status = 'Pending';
    $test->test_date = $testDate;
    $test->test_type = 'Self';
    $test->question_time = $questionTime;
    $test->total_questions = count($questions);
    $test->book_id = $book;
    $test->class_id = Auth::user()->class_id;
    $test->board_id = Auth::user()->board_id;
    $test->save();
    foreach ($questions as $question) {
      $testChild = new TestChild();
      $testChild->test_id = $test->id;
      $testChild->question_id = $question->id;
      $testChild->save();
    }
    return $test->id;
  }
}
