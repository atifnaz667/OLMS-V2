<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use Illuminate\Support\Facades\View;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class ChapterControlloer extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {

    $results = DropdownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return View::make('chapters.index', compact('books', 'boards', 'classes'));
  }


  public function getChapters(Request $request)
  {
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:name,id'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      $perPage = $request->input('perPage', 10);
      $sort = $request->input('sort_by', 'id');
      $sort_order = $request->input('sort_order', 'asc');
      $table = $request->input('table', 'chapters');
      $sorting = $table . '.' . $sort;

      $board = $request->input('board_id');
      $book = $request->input('book_id');
      $class = $request->input('class_id');

      $chapters = Chapter::with(['board', 'book', 'class'])
        ->when($board, function ($query) use ($board) {
          $query->whereHas('board', function ($query) use ($board) {
            $query->where('id', $board);
          });
        })
        ->when($book, function ($query) use ($book) {
          $query->whereHas('book', function ($query) use ($book) {
            $query->where('id', $book);
          });
        })
        ->when($class, function ($query) use ($class) {
          $query->whereHas('class', function ($query) use ($class) {
            $query->where('id', $class);
          });
        })
        ->orderBy($sorting, $sort_order)->paginate($perPage);

      $data = $chapters->map(function ($chapter) {
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
        'message' => 'Chapters retrieved successfully',
        'data' => $data,
        'current_page' => $chapters->currentPage(),
        'last_page' => $chapters->lastPage(),
        'per_page' => $chapters->perPage(),
        'total' => $chapters->total(),
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
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
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

  public function chapterDropDown(Request $request)
  {
    // Validate input
    $rules = [
      'board' => 'integer|exists:boards,id',
      'book' => 'integer|exists:books,id',
      'class' => 'integer|exists:classes,id',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $chapters = Chapter::where('board_id', $request->input('board'))
        ->where('book_id', $request->input('book'))
        ->where('class_id', $request->input('class'))
        ->select('id', 'name')
        ->get();

      return response()->json([
        'status' => 'success',
        'Chapters' => $chapters,
      ], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }
}
