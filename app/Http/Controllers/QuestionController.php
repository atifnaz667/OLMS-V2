<?php

namespace App\Http\Controllers;

use App\Helpers\DropdownHelper;
use App\Models\McqChoice;
use App\Models\Question;
use App\Models\SlAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
  public function addQuestion(Request $request)
  {
    $results = DropdownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('questions.add', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }
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

    $query = Question::orderBy($sort, $sort_order);

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
    return view('questions.index', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'topic_id' => 'required|exists:topics,id',
      'questions' => 'required|array',
      'questions.*.question_type' => 'required|string|in:long,short,mcq',
      'questions.*.description' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 400);
    }

    try {
      DB::beginTransaction();
      $questions = $request->input('questions');
      $topic_id = $request->input('topic_id');

      foreach ($questions as $question) {
        $insertData = [
          'topic_id' => $topic_id,
          'question_type' => $question['question_type'],
          'description' => $question['description'],
        ];
        $question_id = Question::insertGetId($insertData);

        if ($question['question_type'] === 'short') {
          $slanswerData = [
            'question_id' => $question_id,
            'answer' => $question['answer'],
          ];
          SLAnswer::insert($slanswerData);
        } elseif ($question['question_type'] === 'mcq') {
          foreach ($question['mcqs'] as $choice) {
            $choiceData = [
              'question_id' => $question_id,
              'choice' => $choice['choice'],
              'is_true' => $choice['is_true'],
              'reason' => $choice['reason'],
            ];
            MCQChoice::insert($choiceData);
          }
        }
      }
      DB::commit();
      return response()->json(['message' => 'Questions created successfully'], 201);
    } catch (\Exception $e) {
      DB::rollBack();

      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }


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

    $question = Question::findOrFail($id);
    return response()->json(['Question' => $question], 200);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'topic_id' => 'sometimes|exists:topics,id',
      'question_type' => 'sometimes|in:long,short,mcq',
      'description' => 'sometimes|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 400);
    }

    try {
      $question = Question::findOrFail($id);
      $question->update($request->all());

      return response()->json(['message' => 'Question updated successfully', 'data' => $question], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

  public function destroy($id)
  {
  }
}
