<?php

namespace App\Http\Controllers;

use App\Models\McqChoice;
use App\Models\Test;
use App\Models\TestChild;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttemptTestController extends Controller
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
    public function create(Request $req)
    {
      $result = $this->validateTest($req);
      if ($result['status'] == 'error') {
        return response()->json(['status' => 'error', 'message' => $result['message']], 422);
      }
      $test = $result['test'];
      if ($test->attempted_child_count == 0) {
        return view('test.Instructions',['test'=>$test]);
      }else{
        return view('test.attempt-test',['test_id'=>$req->test->id]);
      }
    }


    public function attemptTestAjax(Request $req)
    {
      $test = Test::find($req->test_id);
      $attemptedCount = TestChild::where([['test_id',$req->test_id],['is_viewed',1]])->count();
      if ($attemptedCount == 0) {
        $testChild = TestChild::where('test_id',$test->id)->first();
        $testChild->is_viewed = 1;
        $testChild->viewed_at = date("Y-m-d H:i:s");
        $testChild->save();
        $childToAttempt = TestChild::with('question.mcqChoices')->find($testChild->id);
      }else{
        $testChild = TestChild::with('question.mcqChoices')->where([['test_id',$req->test_id],['is_viewed',1]])->orderBy('id','desc')->first();
        if ($test->question_time >  strtotime(date("Y-m-d H:i:s")) - strtotime($testChild->viewed_at )) {
          $childToAttempt = $testChild;
        }else{
          $testChild = TestChild::where([['test_id',$req->test_id],['is_viewed',0]])->first();
          $testChild->is_viewed = 1;
          $testChild->viewed_at = date("Y-m-d H:i:s");
          $testChild->save();
          $childToAttempt = TestChild::with('question.mcqChoices')->find($testChild->id);
        }

      }
      return view('test.attempt-test-ajax',['test'=>$test, 'attemptedCount'=>$attemptedCount,'childToAttempt'=>$childToAttempt]);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $testChild = TestChild::with('test')->find($request->test_child_id);
        $mcq_id = null;
        if ($testChild->test->question_time >=  strtotime(date("Y-m-d H:i:s")) - strtotime($testChild->viewed_at )) {
          $mcq_id = $request->mcq_id;
        }
        $mcq = McqChoice::find($mcq_id);
        $testChild->mcq_choice_id;
        $testChild->is_correct = $mcq->is_true ?? 0;
        $testChild->save();

      return view('test.attempt-test',['test_id'=>$request->test_id]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $req)
    {
      return view('test.attempt-test',['test_id'=>$req->test_id]);
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

    public function validateTest($req){
      $rules = array(
        'test_id' => 'required|int|exists:tests,id',
      );
      $validator = Validator::make($req->all(), $rules);
      if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
      }
      try {
        $test  = Test::withCount(['testChildren as attempted_child_count' => function ($query) {
          $query->where('is_viewed', 1);
          }])->find($req->test_id);
        if ($test->created_for != Auth::user()->id) {
          return ['status' => 'error', 'message' => 'Invalid Test Requested'];
        }elseif($test->test_date > date('Y-m-d')){
          return ['status' => 'error', 'message' => "You can't attempt test before test date"];
        }elseif($test->status == 'Completed'){
          return ['status' => 'error', 'message' => "You have already taken the test"];
        }
        return ['status'=>'success', 'test'=>$test];
      } catch (\Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);
        return response()->json(['status' => 'error', 'message' => $message], 500);
      }
    }
}
