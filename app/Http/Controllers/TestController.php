<?php

namespace App\Http\Controllers;

use App\Models\AssignUser;
use App\Models\Book;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestChild;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
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
    if (Auth::user()->id == 1) {
      # code...
    }else{
      $parent_id = Auth::user()->id;
      $students = AssignUser::with('child')->where('parent_id',$parent_id)->get();
    }
    return view('test.add',['students'=>$students]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$createdBy = Auth::user()->id;
    $testDate = $request->testDate ?? Date('Y-m-d');
    $totalQuestions = $request->totalQuestions ?? 10;
    $topics = $request->topics;
    $users = $this->getUsersForTest($request);
    foreach ($users as $user) {
      $this->storeTest($topics, $totalQuestions, $createdBy, $user->id, $testDate);
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

  public function storeTest($topics, $totalQuestions, $createdBy, $createdFor,$testDate){
    $questions = Question::inRandomOrder()->whereIn('topic_id',$topics)->limit($totalQuestions)->get();
    $test = new Test();
    $test->created_for = $createdFor;
    $test->created_by = $createdBy;
    $test->status = 'Pending';
    $test->test_date = $testDate;
    $test->save();
    foreach ($questions as $question) {
      $testChild = new TestChild;
      $testChild->test_id = $test->id;
      $testChild->question_id = $question->id;
      $testChild->save();
    }
  }

  public function getUsersForTest(Request $req){
    $role_id = Auth::user()->role_id;
    $board_id = $req->board_id;
    $class_id = $req->class_id;
    $assignedUsers = null;
    if ($role_id == 3) {
      $assignedUsers = $req->users;
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
}
