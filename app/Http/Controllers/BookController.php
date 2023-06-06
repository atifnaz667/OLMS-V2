<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Board;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getBoardBookClass(Request $request)
  {
    $search = $request->input('search');
    $books = Book::when($search, function ($search, $q) {
      return $search->where('name', 'like', '%' . $q . '%');
    })->get();
    $boards = Board::when($search, function ($search, $q) {
      return $search->where('name', 'like', '%' . $q . '%');
    })->get();
    $classes = Classes::when($search, function ($search, $q) {
      return $search->where('name', 'like', '%' . $q . '%'); // Filter the query by name if a search query is present
    })->get();
    return ['Books' => $books, 'Boards' => $boards, 'Classes' => $classes];
  }

  public function index(Request $request)
  {
    $search = $request->input('search');
    $books = Book::when($search, function ($search, $q) {
      return $search->where('name', 'like', '%' . $q . '%');
    })->get();
    $boards = Board::when($search, function ($search, $q) {
      return $search->where('name', 'like', '%' . $q . '%');
    })->get();
    $classes = Classes::when($search, function ($search, $q) {
      return $search->where('name', 'like', '%' . $q . '%'); // Filter the query by name if a search query is present
    })->get();
    return View::make('books.index', compact('books', 'boards', 'classes'));
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
      'name' => 'required|string|max:125|unique:books',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      DB::transaction(function () use ($request) {
        Book::create([
          'name' => $request->name,
        ]);
      });
      return response()->json(['status' => 'success', 'message' => 'Book stored successfully'], 200);
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
      'id' => 'required|int|exists:books,id',
    );
    $validator = Validator::make($data, $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {

      $book = Book::where('id', $id)->first();
      return response()->json(['Book' => $book], 200);
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
      'id' => 'required|int|exists:books,id',
      'name' => 'required|string|max:125',
    );

    $validator = Validator::make($data, $rules);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }

    try {
      $book = Book::findOrFail($id);
      $book->update($request->all());

      return response()->json(['status' => 'success', 'message' => 'Book updated successfully', 'book' => $book], 200);
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
      'id' => 'required|int|exists:books,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      DB::transaction(function () use ($id) {
        Book::findOrFail($id)->delete();
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Book deleted successfully',
      ], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }



  public function allBooks(Request $request)
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
      $search = $request->input('search', '');

      // Get Books with pagination and sorting
      $books = Book::when($search, function ($query) use ($search) {
        return $query->where('name', 'like', '%' . $search . '%');
      })
        ->orderBy($sort, $sort_order)
        ->paginate($perPage);
      return response()->json(['Books' => $books], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }
}
