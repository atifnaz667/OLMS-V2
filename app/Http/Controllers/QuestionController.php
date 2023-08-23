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

    $questions = Question::orderBy($sort, $sort_order)->where('question_type', '!=', 'mcq')
      ->when($topicId,function($q)use($topicId){
        $q->where('topic_id', $topicId);
      })
      ->when($difficulty_level,function($q)use($difficulty_level){
        $q->where('difficulty_level', $difficulty_level);
      })
      ->when($question_nature,function($q)use($question_nature){
        $q->where('question_nature', $question_nature);
      })
      ->when($question_type,function($q)use($question_type){
        $q->where('question_type', $question_type);
      })
      ->when($searchQuery,function($q)use($searchQuery){
        $q->where('description', 'like', '%' . $searchQuery . '%');
      })
      ->when($chapter_id,function($q)use($chapter_id){
        $q->whereHas('topic',function($q)use($chapter_id){
          $q->where('chapter_id', $chapter_id);
        });
      })
      ->when($book_id,function($q)use($book_id){
        $q->whereHas('topic',function($q)use($book_id){
          $q->whereHas('chapter',function($q)use($book_id){
            $q->where('book_id', $book_id);
          });
        });
      })
      ->when($class_id,function($q)use($class_id){
        $q->whereHas('topic',function($q)use($class_id){
          $q->whereHas('chapter',function($q)use($class_id){
            $q->where('class_id', $class_id);
          });
        });
      })
      ->when($board_id,function($q)use($board_id){
        $q->whereHas('topic',function($q)use($board_id){
          $q->whereHas('chapter',function($q)use($board_id){
            $q->where('board_id', $board_id);
          });
        });
      })
      ->paginate($perPage);

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
          'difficulty_level' => $question['difficulty_level'],
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

    return response()->json(['Question' => $question], 200);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'question' => 'sometimes|string',
      'answer' => 'required',
      'question_nature' => 'required',
      'question_type' => 'required',
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
