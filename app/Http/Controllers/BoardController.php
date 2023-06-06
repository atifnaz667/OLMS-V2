<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class BoardController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $search = $request->input('search');
    $boards = Board::when($search, function ($search, $q) {
      return $search->where('name', 'like', '%' . $q . '%');
    })->get();
    return View::make('boards.index', compact('boards'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $rules = array(
      'name' => 'required|string|max:125|unique:boards',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      DB::transaction(function () use ($request) {
        Board::create([
          'name' => $request->name,
        ]);
      });
      return response()->json(['status' => 'success', 'message' => 'Board stored successfully'], 200);
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
      'id' => 'required|int|exists:boards,id',
    );
    $validator = Validator::make($data, $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {

      $board = Board::where('id', $id)->first();
      return response()->json(['Board' => $board], 200);
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
      'id' => 'required|int|exists:boards,id',
      'name' => 'required|string|max:125',
    );

    $validator = Validator::make($data, $rules);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }

    try {
      $board = Board::findOrFail($id);
      $board->update($request->all());

      return response()->json(['status' => 'success', 'message' => 'Board updated successfully', 'board' => $board], 200);
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
      'id' => 'required|int|exists:boards,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      DB::transaction(function () use ($id) {
        Board::findOrFail($id)->delete();
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Board deleted successfully',
      ], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }



  public function allBoards(Request $request)
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

      // Get boards with pagination and sorting
      $boards = Board::orderBy($sort, $sort_order)->paginate($perPage);

      return response()->json(['Boards' => $boards], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }
}
