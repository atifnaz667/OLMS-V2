<?php

namespace App\Http\Controllers;

use App\Models\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class QuestionTypeController extends Controller
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
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $rules = array(
      'type' => 'required|string|max:125|unique:question_types',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      DB::transaction(function () use ($request) {
        QuestionType::create([
          'type' => $request->type,
        ]);
      });
      return response()->json(['status' => 'success', 'message' => 'Question Type stored successfully'], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $data = ['id' => $id];
    $rules = [
      'id' => 'required|int|exists:question_types,id',
    ];
    $validator = Validator::make($data, $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      $questionType = QuestionType::where('id', $id)->first();
      return response()->json(['questionType' => $questionType], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(QuestionType $questionType)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {


    $data = array_merge(['id' => $id], $request->all());

    $rules = array(
      'id' => 'required|int|exists:question_types,id',
      'type' => 'required|string|max:125|unique:question_types',
    );

    $validator = Validator::make($data, $rules);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }

    try {
      $questionType = QuestionType::findOrFail($id);
      $questionType->update($request->all());

      return response()->json(['status' => 'success', 'message' => 'Question Type updated successfully', 'QuestionType' => $questionType], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(QuestionType $questionType)
  {
    //
  }
}
