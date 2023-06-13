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

    $query = Question::orderBy($sort, $sort_order)->where('question_type', '!=', 'mcq');

    if ($topicId) {
      $query->where('topic_id', $topicId); // Apply the filter by topic ID
    }

    $questions = $query->paginate($perPage);

    if ($request->check) {
      $data = $questions->map(function ($question) {
        return [
          'id' => $question->id,
          'question_type' => $question->question_type,
          'question_nature' => $question->question_nature,
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
      'questions.*.question_nature' => 'required|string|in:Conceptual,Exercise',
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
          'question_type' => $question['question_type'],
          'question_nature' => $question['question_nature'],
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

    $question = Question::with('answer')->findOrFail($id);

    return response()->json(['Question' => $question], 200);
  }


  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'question' => 'sometimes|string|max:255',
      'answer' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }

    try {
      $question = Question::findOrFail($id);
      $question->update(['description' => $request->input('question')]);

      $answer = $question->answer;
      $answer->update(['answer' => $request->input('answer')]);

      return response()->json(['status' => 'success', 'message' => 'Question and answer updated successfully', 'data' => $question], 200);
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
    $validator = Validator::make(['id' => $id], [
      'id' => 'required|int|exists:questions,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      DB::transaction(function () use ($id) {
        // Delete the associated records in the Slanswer table
        Slanswer::where('question_id', $id)->delete();

        // Delete the question
        Question::findOrFail($id)->delete();
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Question and associated records deleted successfully',
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
