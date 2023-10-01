<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    try {
      $search = $request->input('search');
      $classes = Classes::when($search, function ($search, $q) {
        return $search->where('name', 'like', '%' . $q . '%'); // Filter the query by name if a search query is present
      })->get();
      return response()->json(['Classes' => $classes], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }


  /**
   * Store a newly created resource in storage.
   * Display a listing of the resource.
   * Display a listing of the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $rules = array(
      'name' => 'required|string|max:125|unique:classes',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      DB::transaction(function () use ($request) {
        Classes::create([
          'name' => $request->name,
        ]);
      });
      return response()->json(['status' => 'success', 'message' => 'Class stored successfully'], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $data = ['id' => $id];
    $rules = array(
      'id' => 'required|int|exists:classes,id',
    );
    $validator = Validator::make($data, $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {

      $class = Classes::where('id', $id)->first();
      return response()->json(['Class' => $class], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $data = array_merge(['id' => $id], $request->all());

    $rules = array(
      'id' => 'required|int|exists:classes,id',
      'name' => 'required|string|max:125',
    );

    $validator = Validator::make($data, $rules);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }

    try {
      $class = Classes::findOrFail($id);
      $class->update($request->all());

      return response()->json(['status' => 'success', 'message' => 'Class updated successfully', 'Class' => $class], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json(['status' => 'error', 'message' => $message], 500);
    }
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $validator = Validator::make(['id' => $id], [
      'id' => 'required|int|exists:classes,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      DB::transaction(function () use ($id) {
        Classes::findOrFail($id)->delete();
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Class deleted successfully',
      ], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }


  public function allClasses(Request $request)
  {
    // Validate input
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:name,id'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {

      // Set default values
      $perPage = $request->input('perPage', 10);
      $sort = $request->input('sort_by', 'name');
      $sort_order = $request->input('sort_order', 'asc');

      // Get Classes with pagination and sorting
      $classes = Classes::orderBy($sort, $sort_order)->paginate($perPage);

      return response()->json(['Classes' => $classes], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }
}
