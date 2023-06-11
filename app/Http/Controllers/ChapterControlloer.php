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

  public function addChapter(Request $request)
  {
    $results = DropdownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('chapters.add', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

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

    $rules = [
      'board_id' => 'required|exists:boards,id',
      'book_id' => 'required|exists:books,id',
      'class_id' => 'required|exists:classes,id',
      'chapters.*' => 'required',
      'chapter.*.chapter_no' => 'required',
      'chapter.*.name' => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }


    try {
      foreach ($request->chapters as $chap) {
        $chapter = new Chapter;
        $chapter->board_id = $request->board_id;
        $chapter->book_id = $request->book_id;
        $chapter->class_id = $request->class_id;
        $chapter->book_edition = $request->book_edition;
        $chapter->chapter_no = $chap['chapter_no'];
        $chapter->name = $chap['name'];
        $chapter->save();
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Chapter stored successfully',
      ], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {

    $chapter = Chapter::where('id', $id)
      ->first();


    if (!$chapter) {
      return response()->json(['status' => 'error', 'message' => 'Chapter not found'], 404);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Chapter retrieved successfully',
      'chapter' => $chapter
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $chapter = Chapter::find($id);
    if (!$chapter) {
      return response()->json(['status' => 'error', 'message' => 'Chapter not found'], 404);
    }
    $validator = Validator::make($request->all(), [
      // 'board_id' => 'integer|exists:boards,id',
      // 'book_id' => 'integer|exists:books,id',
      // 'class_id' => 'integer|exists:classes,id',
      // 'book_edition' => 'string',
      'chapter_no' => 'string',
      'name' => 'string',
    ]);

    // Return error if validation fails
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      // Update the chapter with the input values
      $chapter->board_id = $request->input('board_id', $chapter->board_id);
      $chapter->book_id = $request->input('book_id', $chapter->book_id);
      $chapter->class_id = $request->input('class_id', $chapter->class_id);
      $chapter->book_edition = $request->input('book_edition', $chapter->book_edition);
      $chapter->chapter_no = $request->input('chapter_no', $chapter->chapter_no);
      $chapter->name = $request->input('name', $chapter->name);

      // Save the chapter
      $chapter->save();

      // Return success response
      return response()->json(['status' => 'success', 'message' => 'Chapter updated successfully', 'data' => $chapter]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }


  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $chapter = Chapter::find($id);

    // Check if chapter exists
    if (!$chapter) {
      return response()->json(['status' => 'error', 'message' => 'Chapter not found'], 404);
    }

    try {
      // Check if the chapter has any topics
      if ($chapter->topics()->count() > 0) {
        return response()->json(['status' => 'error', 'message' => 'Cannot delete chapter with topics'], 400);
      }

      // Delete the chapter
      $chapter->delete();

      return response()->json(['status' => 'success', 'message' => 'Chapter deleted successfully'], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
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
