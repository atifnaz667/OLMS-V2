<?php

namespace App\Http\Controllers;

use App\Models\McqChoice;
use App\Models\Test;
use App\Models\TestChild;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return view('test.attempt-test',['test_id'=>$test->id]);
      }
    }


    public function attemptTestAjax(Request $req)
    {
      $rules = array(
        'test_id' => 'required|int|exists:tests,id',
      );
      $validator = Validator::make($req->all(), $rules);
      if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
      }
      $test = Test::find($req->test_id);
      if ($test->status == 'Attempted') {
        return view('test.test-completed');
      }
      $attemptedCount = TestChild::where([['test_id',$req->test_id],['is_viewed',1]])->count();
      if ($attemptedCount == 0) {
        $testChild = TestChild::where('test_id',$test->id)->first();
        $testChild->is_viewed = 1;
        $testChild->viewed_at = date("Y-m-d H:i:s");
        $testChild->save();
        $childToAttempt = TestChild::with('question.mcqChoices')->find($testChild->id);
      }else{
        $testChild = TestChild::with('question.mcqChoices')->where([['test_id',$req->test_id],['is_viewed',1]])->orderBy('id','desc')->first();
        if (!$testChild) {
          return view('test.test-completed');
        }
        if ($test->question_time >  strtotime(date("Y-m-d H:i:s")) - strtotime($testChild->viewed_at )  && $testChild->is_attempted == 0) {
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
        $rules = array(
          'test_child_id' => 'required|int|exists:test_children,id',
          'test_id' => 'required|int|exists:tests,id',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $result = $this->validateTest($request);
        if ($result['status'] == 'error') {
          return response()->json(['status' => 'error', 'message' => $result['message']], 422);
        }
        try{
          $isExpired = 1;
          DB::transaction(function () use($request, &$isExpired){
            $testChild = TestChild::with('test')->find($request->test_child_id);
            $mcq_id = null;
            if ($testChild->test->question_time >=  strtotime(date("Y-m-d H:i:s")) - strtotime($testChild->viewed_at )) {
              $mcq_id = $request->mcq_id;
              $isExpired = 0;
            }
            $mcq = McqChoice::find($mcq_id);

            $testChild = TestChild::find($request->test_child_id);
            $nextChild = TestChild::where([['test_id',$testChild->test_id],['id','>',$testChild->id]])->first();
            $test = Test::find($testChild->test_id);
            if (!$nextChild) {
              $test->status = 'Attempted';
              $test->attempted_at = date('Y-m-d H:i:s');
            }
            if ($mcq->is_true == 1 && $isExpired == 0) {
              $test->obtained_marks = $test->obtained_marks + 1;
            }
              $test->save();

            if ($isExpired == 1) {
              return response()->json(['status' => 'success', 'message' => 'Time limit for the question exceeded.']);
            }
            $testChild->mcq_choice_id = $mcq_id;
            $testChild->is_correct = $mcq->is_true ?? 0;
            $testChild->is_attempted = 1;
            $testChild->save();

          });


          return response()->json(['status' => 'success', 'message' => 'Answer stored successfully']);
        } catch (\Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);
        return response()->json(['status' => 'error', 'message' => $message], 500);
      }
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
        return ['status' => 'error', 'message' => $validator->errors()->first()];
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
        }elseif($test->expiry_date != null && $test->expiry_date < date('Y-m-d')){
          return ['status' => 'error', 'message' => "This test is expired now"];
        }
        return ['status'=>'success', 'test'=>$test];
      } catch (\Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);
        return response()->json(['status' => 'error', 'message' => $message], 500);
      }
    }
}
