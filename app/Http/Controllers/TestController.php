<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\AssignUser;
use App\Models\Book;
use App\Models\Chapter;
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

class TestController extends Controller
{

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
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

      $tests = Test::
        when($from, function ($query) use ($from) {
          $query->where('created_at', '>',$from);
        })
        ->when($to, function ($query) use ($to) {
          $query->where('created_at', '<',$to);
        })
        ->when($status, function ($query) use ($status) {
          $query->where('status',$status);
        })
        ->orderBy($sorting, $sort_order)->paginate($perPage);

      $data = $tests->map(function ($chapter) {
        return [
          'id' => $chapter->id,
          'board' => $chapter->board->name,
          'book' => $chapter->book->name,
          'class' => $chapter->class->name,
          'book_edition' => $chapter->book_edition,
          'chapter_no' => $chapter->chapter_no,
          'name' => $chapter->name,
          'created_at' => $chapter->created_at,
          'updated_at' => $chapter->updated_at,
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
    if (Auth::user()->role_id == 1) {
      # code...
    }else{
      $parent_id = Auth::user()->id;
      $students = AssignUser::with('child')->where('parent_id',$parent_id)->get();
    }

    $timeOptions = Helpers::getTimeForQuestions();
    return view('test.add',['students'=>$students,'timeOptions'=>$timeOptions]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
    $rules = array(
      'testDate' => 'required',
      'totalQuestions' => 'required|int|max:100',
      'chapters' => 'required',
      'testUserId' => 'required',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      DB::beginTransaction();
        $createdBy = Auth::user()->id;
        $testDate = $request->testDate ?? Date('Y-m-d');
        $totalQuestions = $request->totalQuestions ?? 10;
        $chapters = $request->chapters;
        $questionTime = $request->questionTime;
        $users = $this->getUsersForTest($request);
        foreach ($users as $user) {
          $isValidUser = AssignUser::where([['parent_id',$createdBy],['child_id',$user->id]])->first();
          if ((Auth::user()->role_id != 1 && !$isValidUser) || $user->status != 'active') {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Invalid User provided for test creation'], 422);
          }
          $storeTest = $this->storeTest($chapters, $totalQuestions, $createdBy, $user->id, $testDate,$questionTime);
          if (!$storeTest) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Questions not found against these chapters'], 422);
          }
        }
      DB::commit();
      return response()->json(['status' => 'success', 'message' => 'Test created successfully'], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }

	}

	/**
	 * Display the specified resource.
	 */
	public function show(Test $test)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Test $test)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Test $test)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Test $test)
	{
		//
	}

  public function storeTest($chapters, $totalQuestions, $createdBy, $createdFor,$testDate,$questionTime){
    $topics = Topic::whereIn('chapter_id',$chapters)->get()->pluck('id');
    $questions = Question::inRandomOrder()->where('question_type','mcq')->whereIn('topic_id',$topics)->limit($totalQuestions)->get();
    if (count($questions) == 0) {
      return false;
    }
    $test = new Test();
    $test->created_for = $createdFor;
    $test->created_by = $createdBy;
    $test->status = 'Pending';
    $test->test_date = $testDate;
    $test->question_time = $questionTime;
    $test->save();
    foreach ($questions as $question) {
      $testChild = new TestChild;
      $testChild->test_id = $test->id;
      $testChild->question_id = $question->id;
      $testChild->save();
    }
  }

  public function getUsersForTest($req){
    $role_id = Auth::user()->role_id;
    $board_id = $req->board_id;
    $class_id = $req->class_id;
    $assignedUsers = null;
    if ($role_id == 2) {
      $assignedUsers = $req->testUserId;
    }
    $users = User::where([['status','active']])
    ->when($board_id,function($q)use($board_id){
      $q->where('board_id',$board_id);
    })
    ->when($class_id,function($q)use($class_id){
      $q->where('class_id',$class_id);
    })
    ->when($assignedUsers,function($q)use($assignedUsers){
      $q->whereIn('id',$assignedUsers);
    })->get();

    return $users;
  }

  public function getBooksForTest(Request $req){
    $books =  Book::getBooksForParent($req->userId);
    $options = '<option value="">Select Book</option>';
    foreach ($books as $book) {
      $options = $options.' <option value="'.$book->id.'">'.$book->name.'</option>';
    }
    return $options;
  }

  public function getChaptersForTest(Request $req){
    $user = User::find($req->userId);
    $chapters = Chapter::where([['book_id',$req->bookId],['board_id',$user->board_id],['class_id',$user->class_id]])->get();
    $cols = ' <div class="col-12 mb-2"> <input class="form-check-input " style="margin-right:1em" id="select-all" onclick="selectCheckboxes()" type="checkBox"> Select All</div>';
    foreach ($chapters as $chapter) {
      $cols = $cols.' <div class="col-sm-3 col-6 mb-2"> <input style="margin-right:1em" onclick="selectCheckbox()" type="checkBox" name="chapters[]" class="form-check-input checkboxes" value="'.$chapter->id.'"> '.$chapter->name.'</div>';
    }
    if (count($chapters) == 0) {
      $cols = ' <div class="col-12"> <h6>No Chapters found against this book </h6></div>';
    }
    if (!$req->bookId) {
      $cols = ' <div class="col-12"> <h6>Please select book </h6></div>';
    }
    return $cols;
  }
}
