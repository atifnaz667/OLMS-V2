<?php

namespace App\Http\Controllers;

use App\Helpers\DropDownHelper;
use App\Models\McqChoice;
use App\Models\Question;
use App\Models\SlAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Board;
use App\Models\Classes;
use App\Models\AssignRole;
use App\Models\Chapter;
use App\Models\Topic;

class QuestionController extends Controller
{
  public function addQuestion(Request $request)
  {
    $user = Auth::user();
    $role_id = $user->role_id;

    // Initialize variables
    $boards = [];
    $classes = [];
    $books = [];

    if ($role_id == 5) {
      $results = DropDownHelper::getBoardBookClass();
      // If the user has role_id 5, retrieve data based on their assignments
      $assignRoles = AssignRole::where('staff_id', $user->id)->get();

      // Collect unique board_ids, class_ids, and subject_ids
      $board_ids = $assignRoles->pluck('board_id')->unique();
      $class_ids = $assignRoles->pluck('class_id')->unique();
      $subject_ids = $assignRoles->pluck('subject_id')->unique();

      // Retrieve boards, classes, and books based on the unique IDs
      $boards = Board::whereIn('id', $board_ids)->get();
      $classes = Classes::whereIn('id', $class_ids)->get();
      $books = Book::whereIn('id', $subject_ids)->get();
      $questionType = $results['questionType'];
    } else {
      $results = DropDownHelper::getBoardBookClass();
      $books = $results['Books'];
      $boards = $results['Boards'];
      $classes = $results['Classes'];
      $questionType = $results['questionType'];
    }


    return view('questions.add', ['books' => $books, 'boards' => $boards, 'classes' => $classes, 'questionType' => $questionType]);
  }
  public function index(Request $request)
  {
    $user = Auth::user();
    $role_id = $user->role_id;
    $user_id = $user->id;

    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:description,id',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    $perPage = $request->input('perPage', 10);
    $sort = $request->input('sort_by', 'description');
    $sort_order = $request->input('sort_order', 'asc');
    $topicId = $request->input('topic_id');
    $question_type = $request->input('type');
    $board_id = $request->input('board_id');
    $class_id = $request->input('class_id');
    $book_id = $request->input('book_id');
    $chapter_id = $request->input('chapter_id');
    $difficulty_level = $request->input('difficulty_level');
    $question_nature = $request->input('nature');
    $searchQuery = $request->input('searchQuery');

    if ($role_id == 1) {
      $questions = Question::with('topic')->orderBy($sort, $sort_order);
    } else {
      $questions = Question::with('topic')->orderBy($sort, $sort_order)->where('user_id', $user_id);
    }

    $questions = $questions->where('question_type', '!=', 'mcq')
      ->when($topicId, function ($q) use ($topicId) {
        $q->where('topic_id', $topicId);
      })
      ->when($difficulty_level, function ($q) use ($difficulty_level) {
        $q->where('difficulty_level', $difficulty_level);
      })
      ->when($question_nature, function ($q) use ($question_nature) {
        $q->where('question_nature', $question_nature);
      })
      ->when($question_type, function ($q) use ($question_type) {
        $q->where('question_type', $question_type);
      })
      ->when($searchQuery, function ($q) use ($searchQuery) {
        $q->where('description', 'like', '%' . $searchQuery . '%');
      })
      ->when($chapter_id, function ($q) use ($chapter_id) {
        $q->whereHas('topic', function ($q) use ($chapter_id) {
          $q->where('chapter_id', $chapter_id);
        });
      })
      ->when($book_id, function ($q) use ($book_id) {
        $q->whereHas('topic', function ($q) use ($book_id) {
          $q->whereHas('chapter', function ($q) use ($book_id) {
            $q->where('book_id', $book_id);
          });
        });
      })
      ->when($class_id, function ($q) use ($class_id) {
        $q->whereHas('topic', function ($q) use ($class_id) {
          $q->whereHas('chapter', function ($q) use ($class_id) {
            $q->where('class_id', $class_id);
          });
        });
      })
      ->when($board_id, function ($q) use ($board_id) {
        $q->whereHas('topic', function ($q) use ($board_id) {
          $q->whereHas('chapter', function ($q) use ($board_id) {
            $q->where('board_id', $board_id);
          });
        });
      })
      ->paginate($perPage);
    if ($request->check) {
      $data = $questions->map(function ($question) {
        return [
          'id' => $question->id,
          'topic_name' => $question->topic->name,
          'question_type' => $question->question_type,
          'question_nature' => $question->question_nature,
          'difficulty_level' => $question->difficulty_level,
          'description' => $question->description,
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Chapters retrieved successfully',
        'data' => $data,
        'current_page' => $questions->currentPage(),
        'last_page' => $questions->lastPage(),
        'per_page' => $questions->perPage(),
        'total' => $questions->total(),
      ]);
    }



    // Initialize variables
    $boards = [];
    $classes = [];
    $books = [];

    if ($role_id == 5) {
      $results = DropDownHelper::getBoardBookClass();
      // If the user has role_id 5, retrieve data based on their assignments
      $assignRoles = AssignRole::where('staff_id', $user->id)->get();

      // Collect unique board_ids, class_ids, and subject_ids
      $board_ids = $assignRoles->pluck('board_id')->unique();
      $class_ids = $assignRoles->pluck('class_id')->unique();
      $subject_ids = $assignRoles->pluck('subject_id')->unique();

      // Retrieve boards, classes, and books based on the unique IDs
      $boards = Board::whereIn('id', $board_ids)->get();
      $classes = Classes::whereIn('id', $class_ids)->get();
      $books = Book::whereIn('id', $subject_ids)->get();
      $questionType = $results['questionType'];
    } else {
      $results = DropDownHelper::getBoardBookClass();
      $books = $results['Books'];
      $boards = $results['Boards'];
      $classes = $results['Classes'];
      $questionType = $results['questionType'];
    }
    return view('questions.index', ['books' => $books, 'boards' => $boards, 'classes' => $classes, 'questionType' => $questionType]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'topic_id' => 'required|exists:topics,id',
      'questions' => 'required|array',
      'question_type' => 'required|string',
      'question_nature' => 'required|string|in:Conceptual,Exercise',
      'questions.*.description' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      DB::beginTransaction();
      $questions = $request->input('questions');
      $topic_id = $request->input('topic_id');
      foreach ($questions as $question) {
        $insertData = [
          'topic_id' => $topic_id,
          'user_id' => Auth::user()->id,
          'question_type' => $request->question_type,
          'question_nature' => $request->question_nature,
          'difficulty_level' => $request->difficulty_level,
          'description' => $question['description'],
        ];
        $question_id = Question::insertGetId($insertData);
        $slanswerData = [
          'question_id' => $question_id,
          'answer' => $question['answer'],
        ];
        SlAnswer::insert($slanswerData);
      }
      DB::commit();
      return response()->json(['status' => 'success', 'message' => 'Questions created successfully'], 201);
    } catch (\Exception $e) {
      DB::rollBack();

      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json(
        [
          'status' => 'error',
          'message' => $message,
        ],
        500
      );
    }
  }

  public function show($id)
  {
    $validator = Validator::make(
      ['id' => $id],
      [
        'id' => 'required|int|exists:questions,id',
      ]
    );

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 'error',
          'message' => $validator->errors()->first(),
        ],
        400
      );
    }

    $question = Question::with('answer')->findOrFail($id);
    $topic_id = $question->topic_id;
    $result = Topic::select('topics.id', 'chapters.id as chapter_id', 'chapters.board_id', 'chapters.book_id', 'chapters.class_id')
      ->join('chapters', 'topics.chapter_id', '=', 'chapters.id')
      ->where('topics.id', '=', $topic_id)
      ->first();
    $topics = Topic::where('chapter_id', $result->chapter_id)->get();
    $chapters = Chapter::where('board_id', $result->board_id)
      ->where('book_id', $result->book_id)
      ->where('class_id', $result->class_id)
      ->get();

    return response()->json(['topic_id' => $topic_id, 'topics' => $topics, 'chapter_id' => $result->chapter_id, 'chapters' => $chapters, 'Question' => $question], 200);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'question' => 'sometimes|string',
      'answer' => 'required',
      'question_nature' => 'required',
      'question_type' => 'required',
      'topic_id_edit' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }

    try {
      $question = Question::findOrFail($id);
      $question->description = $request->input('question');
      $question->question_nature = $request->input('question_nature');
      $question->question_type = $request->input('question_type');
      $question->difficulty_level = $request->input('difficulty_level');
      $question->topic_id = $request->input('topic_id_edit');
      $question->save();

      $answer = $question->answer;
      $answer->update(['answer' => $request->input('answer')]);

      return response()->json(
        ['status' => 'success', 'message' => 'Question and answer updated successfully', 'data' => $question],
        200
      );
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json(
        [
          'status' => 'error',
          'message' => $message,
        ],
        500
      );
    }
  }

  public function destroy($id)
  {
    $validator = Validator::make(
      ['id' => $id],
      [
        'id' => 'required|int|exists:questions,id',
      ]
    );

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 'error',
          'message' => $validator->errors()->first(),
        ],
        400
      );
    }

    try {
      DB::transaction(function () use ($id) {
        // Delete the associated records in the Slanswer table
        Slanswer::where('question_id', $id)->delete();

        // Delete the question
        Question::findOrFail($id)->delete();
      });

      return response()->json(
        [
          'status' => 'success',
          'message' => 'Question and associated records deleted successfully',
        ],
        200
      );
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json(
        [
          'status' => 'error',
          'message' => $message,
        ],
        500
      );
    }
  }
}
