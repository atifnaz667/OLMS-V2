<?php

namespace App\Http\Controllers;

use App\Models\Test;
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
        return view('test.attempt-test');
      }
    }


    public function attemptTestAjax()
    {
      return view('test.attempt-test-ajax');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $req)
    {
      return view('test.attempt-test');
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
