<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\SlAnswer;
use App\Models\McqChoice;
use Illuminate\Http\Request;
use App\Helpers\DropDownHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Board;
use App\Models\Classes;
use App\Models\AssignRole;
use App\Models\TestChild;

class McqChoiceController extends Controller
{
  /**
   * Display a listing of the resource.
   */
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
    $searchQuery = $request->input('searchQuery');
    $board_id = $request->input('board_id');
    $class_id = $request->input('class_id');
    $book_id = $request->input('book_id');
    $chapter_id = $request->input('chapter_id');
    $difficulty_level = $request->input('difficulty_level');

    if ($role_id == 1) {
      $questions = Question::with('topic.chapter')->orderBy($sort, $sort_order);
    } else {
      $questions = Question::with('topic.chapter')->orderBy($sort, $sort_order)->where('user_id', $user_id);
    }

    $questions = $questions->where('question_type', 'mcq')

      ->when($searchQuery, function ($q) use ($searchQuery) {
        $q->where('description', 'like', '%' . $searchQuery . '%');
      })
      ->when($topicId, function ($q) use ($topicId) {
        $q->where('topic_id', $topicId);
      })
      ->when($difficulty_level, function ($q) use ($difficulty_level) {
        $q->where('difficulty_level', $difficulty_level);
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
          'question_nature' => $question->question_nature ?? 'NA',
          'difficulty_level' => $question->difficulty_level ?? 'NA',
          'description' => $question->description,
          'topic' => $question->topic->name,
          'unit_no' => $question->topic->chapter->chapter_no,
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
    } else {
      $results = DropDownHelper::getBoardBookClass();
      $books = $results['Books'];
      $boards = $results['Boards'];
      $classes = $results['Classes'];
    }

    return view('mcq.index', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

  public function addMcqChoioce(Request $request)
  {
    $user = Auth::user();
    $role_id = $user->role_id;

    // Initialize variables
    $boards = [];
    $classes = [];
    $books = [];

    if ($role_id == 5) {
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
    } else {
      $results = DropDownHelper::getBoardBookClass();
      $books = $results['Books'];
      $boards = $results['Boards'];
      $classes = $results['Classes'];
    }

    return view('mcq.add', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'topic_id' => 'required|exists:topics,id',
      'questions' => 'required|array',
      'questions.*.description' => 'required|string',
      'questions.*.option-a' => 'required|string',
      'questions.*.option-b' => 'required|string',
      'questions.*.option-c' => 'required|string',
      'questions.*.option-d' => 'required|string',
      'questions.*.correct-option' => 'required|in:a,b,c,d',
      // 'questions.*.answer' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }

    try {
      DB::beginTransaction();
      $questions = $request->input('questions');
      $topic_id = $request->input('topic_id');
      $mcq = 'mcq';
      foreach ($questions as $question) {
        $insertData = [
          'topic_id' => $topic_id,
          'user_id' => Auth::user()->id,
          'question_type' => $mcq,
          'difficulty_level' => $request->difficulty_level,
          'description' => $question['description'],
        ];
        $question_id = Question::insertGetId($insertData);

        $choiceData = [
          [
            'question_id' => $question_id,
            'choice' => $question['option-a'],
            'is_true' => $question['correct-option'] === 'a' ? 1 : 0,
            'reason' => $question['answer'],
          ],
          [
            'question_id' => $question_id,
            'choice' => $question['option-b'],
            'is_true' => $question['correct-option'] === 'b' ? 1 : 0,
            'reason' => $question['answer'],
          ],
          [
            'question_id' => $question_id,
            'choice' => $question['option-c'],
            'is_true' => $question['correct-option'] === 'c' ? 1 : 0,
            'reason' => $question['answer'],
          ],
          [
            'question_id' => $question_id,
            'choice' => $question['option-d'],
            'is_true' => $question['correct-option'] === 'd' ? 1 : 0,
            'reason' => $question['answer'],
          ],
        ];

        McqChoice::insert($choiceData);
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

  /**
   * Display the specified resource.
   */
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

    $question = Question::with('mcqChoices')->findOrFail($id);

    return response()->json(['Question' => $question], 200);
  }
  /**
   * Display the specified resource.
   */
  public function McqChoioceDetails($id)
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

    $question = Question::with('mcqChoices')->findOrFail($id);

    return response()->json(['Question' => $question], 200);
  }
  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    // Validate the request data
    $request->validate([
      'question' => 'required',
      'option-a' => 'required',
      'option-b' => 'required',
      'option-c' => 'required',
      'option-d' => 'required',
      'correct-option' => 'required',
      // 'answer' => 'required',
    ]);

    // Find the question by ID
    $question = Question::findOrFail($id);

    // Update the question description
    $question->description = $request->input('question');
    $question->save();

    // Update the MCQ choices
    $choicesData = [
      'a' => [
        'id' => $request->input('option-a-id'),
        'choice' => $request->input('option-a'),
        'isTrue' => $request->input('correct-option') === 'option-a' ? 1 : 0,
        'reason' => $request->input('answer'),
      ],
      'b' => [
        'id' => $request->input('option-b-id'),
        'choice' => $request->input('option-b'),
        'isTrue' => $request->input('correct-option') === 'option-b' ? 1 : 0,
        'reason' => $request->input('answer'),
      ],
      'c' => [
        'id' => $request->input('option-c-id'),
        'choice' => $request->input('option-c'),
        'isTrue' => $request->input('correct-option') === 'option-c' ? 1 : 0,
        'reason' => $request->input('answer'),
      ],
      'd' => [
        'id' => $request->input('option-d-id'),
        'choice' => $request->input('option-d'),
        'isTrue' => $request->input('correct-option') === 'option-d' ? 1 : 0,
        'reason' => $request->input('answer'),
      ],
    ];

    foreach ($choicesData as $option => $data) {
      $this->updateMcqChoice($data['id'], $data['choice'], $data['isTrue'], $data['reason']);
    }

    // Return a response indicating the success
    return response()->json([
      'status' => 'Success',
      'message' => 'Question updated successfully',
    ]);
  }

  private function updateMcqChoice($id, $choice, $isTrue, $reason)
  {
    // Find the MCQ choice by ID
    $mcqChoice = McqChoice::findOrFail($id);

    // Update the MCQ choice fields
    $mcqChoice->choice = $choice;
    $mcqChoice->is_true = $isTrue;
    $mcqChoice->reason = $reason;
    $mcqChoice->save();
  }
  /**
   * Remove the specified resource from storage.
   */
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
        // Delete the associated records in the McqChoice table
        TestChild::where('question_id', $id)->delete();
        McqChoice::where('question_id', $id)->delete();

        // Delete the question
        Question::findOrFail($id)->delete();
      });

      return response()->json(
        [
          'status' => 'success',
          'message' => 'Mcq and associated records deleted successfully',
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
