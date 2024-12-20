<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Chapter;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Helpers\DropDownHelper;
use Illuminate\Support\Facades\DB;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{

  public function addTopic(Request $request)
  {
    $results = DropDownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('topics.add', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:name,id'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }
    try {
      $perPage = $request->input('perPage', 10);
      $sort = $request->input('sort_by', 'id');
      $sort_order = $request->input('sort_order', 'asc');
      $table = $request->input('table', 'topics');
      $sorting = $table . '.' . $sort;
      $chapter = $request->input('chapter_id');
      $topics = Topic::where('chapter_id', $chapter)->orderBy($sorting, $sort_order)->paginate($perPage);
      if ($request->check) {
        $data = $topics->map(function ($topic) {
          return [
            'id' => $topic->id,
            'name' => $topic->name,
          ];
        });

        return response()->json([
          'status' => 'success',
          'message' => 'Topic retrieved successfully',
          'data' => $data,
          'current_page' => $topics->currentPage(),
          'last_page' => $topics->lastPage(),
          'per_page' => $topics->perPage(),
          'total' => $topics->total(),
        ]);
      }
      $results = DropDownHelper::getBoardBookClass();
      $books = $results['Books'];
      $boards = $results['Boards'];
      $classes = $results['Classes'];

      return view('topics.index', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return back()->with('error', $message);
    }
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // return $request;
    $validator = Validator::make($request->all(), [
      'chapter_id' => 'required|exists:chapters,id',
      'topics.*.name' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      $topics = $request->input('topics');
      $chapter_id = $request->input('chapter_id');

      $insertData = [];
      foreach ($topics as $topic) {
        $insertData[] = [
          'chapter_id' => $chapter_id,
          'name' => $topic['name']
        ];
      }

      Topic::insert($insertData);

      return response()->json(['status' => 'success', 'message' => 'Topics created successfully'], 201);
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
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $validator = Validator::make(['id' => $id], [
      'id' => 'required|exists:topics,id'
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => 'errors', 'message' => $validator->errors()->first()], 422);
    }
    try {
      $topic = Topic::findOrFail($id);


      return response()->json([
        'status' => 'success',
        'message' => 'Topic retrieved successfully',
        'topic' => $topic
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
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $topicCheck = Topic::find($id);
    if (!$topicCheck)
      return response()->json(['status' => 'error', 'message' => 'Topic not found'], 404);

    // Validate input
    $validator = Validator::make($request->all(), [
      // 'chapter_id' => 'exists:chapters,id',
      'name' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      // Find the topic to update
      $topic = Topic::findOrFail($id);

      // Update the topic
      // $topic->chapter_id = $request->input('chapter_id', $topic->chapter_id);
      $topic->name = $request->input('name', $topic->name);
      $topic->save();

      return response()->json([
        'status' => 'success',
        'message' => 'Topic updated successfully',
        'data' => $topic,
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
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $validator = Validator::make(['id' => $id], [
      'id' => 'required|int|exists:topics,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      DB::transaction(function () use ($id) {
        $topic = Topic::findOrFail($id);

        // Check if the topic_id exists in the questions table
        $questionsWithTopic = Question::where('topic_id', $id)->exists();

        if ($questionsWithTopic) {
          throw new \Exception('This topic is referenced in the questions table and cannot be deleted.');
        }

        $topic->delete();
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Topic deleted successfully',
      ], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }


  public function topics(Request $request)
  {
    $rules = [
      'chapter' => 'integer|exists:chapters,id',
      'chapter' => 'exists:topics,chapter_id',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }
    try {
      $topics = Topic::where('chapter_id', $request->input('chapter'))
        ->select('id', 'name')
        ->get();
      return response()->json(['status' => 'success', 'Topics' => $topics], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }
}
