<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\SlAnswer;
use App\Models\McqChoice;
use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class McqChoiceController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:description,id'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    $perPage = $request->input('perPage', 10);
    $sort = $request->input('sort_by', 'description');
    $sort_order = $request->input('sort_order', 'asc');
    $topicId = $request->input('topic_id'); // Get the topic ID from the request

    $query = Question::orderBy($sort, $sort_order)->where('question_type', 'mcq');

    if ($topicId) {
      $query->where('topic_id', $topicId); // Apply the filter by topic ID
    }

    $questions = $query->paginate($perPage);

    if ($request->check) {
      $data = $questions->map(function ($question) {
        return [
          'id' => $question->id,
          'question_type' => $question->question_type,
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
    $results = DropdownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('mcq.index', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

  public function addMcqChoioce(Request $request)
  {
    $results = DropdownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
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
      'questions.*.description' => 'required|string|max:255',
      'questions.*.option-a' => 'required|string',
      'questions.*.option-b' => 'required|string',
      'questions.*.option-c' => 'required|string',
      'questions.*.option-d' => 'required|string',
      'questions.*.correct-option' => 'required|in:a,b,c,d',
      'questions.*.answer' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }

    try {
      DB::beginTransaction();
      $questions = $request->input('questions');
      $topic_id = $request->input('topic_id');
      $mcq = "mcq";
      foreach ($questions as $question) {
        $insertData = [
          'topic_id' => $topic_id,
          'question_type' => $mcq,
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
    $validator = Validator::make(['id' => $id], [
      'id' => 'required|int|exists:questions,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    $question = Question::with('mcq')->findOrFail($id);

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
      'answer' => 'required',
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
        'isTrue' => ($request->input('correct-option') === 'option-a') ? 1 : 0,
        'reason' => $request->input('answer'),
      ],
      'b' => [
        'id' => $request->input('option-b-id'),
        'choice' => $request->input('option-b'),
        'isTrue' => ($request->input('correct-option') === 'option-b') ? 1 : 0,
        'reason' => $request->input('answer'),
      ],
      'c' => [
        'id' => $request->input('option-c-id'),
        'choice' => $request->input('option-c'),
        'isTrue' => ($request->input('correct-option') === 'option-c') ? 1 : 0,
        'reason' => $request->input('answer'),
      ],
      'd' => [
        'id' => $request->input('option-d-id'),
        'choice' => $request->input('option-d'),
        'isTrue' => ($request->input('correct-option') === 'option-d') ? 1 : 0,
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
  public function destroy(string $id)
  {
    //
  }
}
