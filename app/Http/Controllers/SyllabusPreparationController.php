<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Topic;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyllabusPreparationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $board_id = Auth::user()->board_id;
    $class_id = Auth::user()->class_id;
    $books = Chapter::with('book')
      ->whereIn('id', function ($query) use ($board_id, $class_id) {
        $query
          ->selectRaw('MIN(id)')
          ->from('chapters')
          ->where('board_id', $board_id)
          ->where('class_id', $class_id)
          ->groupBy('book_id');
      })
      ->get()
      ->pluck('book');
    return view('syllabus-preparation.list', ['books' => $books]);
  }
  public function fetchData($bookId, Request $request)
  {
    $board_id = Auth::user()->board_id;
    $class_id = Auth::user()->class_id;
    $questionType = $request->questionType;
    if ($questionType === 'Objective') {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query->join('questions', 'questions.topic_id', '=', 'topics.id')->where('questions.question_type', 'mcq');
      })
        ->where('book_id', $bookId)
        ->where('board_id', $board_id)
        ->where('class_id', $class_id)
        ->get();

      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_type', 'mcq')
        ->distinct()
        ->get();
    } elseif ($questionType === 'Conceptual') {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query
          ->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_nature', 'Conceptual');
      })
        ->where('book_id', $bookId)
        ->where('board_id', $board_id)
        ->where('class_id', $class_id)
        ->get();

      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_nature', 'Conceptual')
        ->distinct()
        ->get();
    } elseif ($questionType === 'Exercise') {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query
          ->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_nature', 'Exercise');
      })
        ->where('book_id', $bookId)
        ->where('board_id', $board_id)
        ->where('class_id', $class_id)
        ->get();

      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_nature', 'Exercise')
        ->distinct()
        ->get();
    } else {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query
          ->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_type', '!=', 'mcq');
      })
        ->where('book_id', $bookId)
        ->where('board_id', $board_id)
        ->where('class_id', $class_id)
        ->get();

      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_type', '!=', 'mcq')
        ->distinct()
        ->get();
    }

    // Fetch chapters and topics based on the book ID

    // Prepare the data to be sent as a response
    $data = [
      'chapters' => $chapters,
      'topics' => $topics,
    ];

    // Return the data as JSON response
    return response()->json($data);
  }
  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    $book_id = $request->bookId;
    $book = Book::findOrFail($book_id);
    $test_type = $request->testType;
    $totalQuestions = $request->totalQuestions;
    $totalLongQuestions = $request->totalLongQuestions ?? 0;
    $totalShortQuestions = $request->totalShortQuestions ?? 0;
    $topics = $request->topics;

    if ($test_type === 'Objective') {
      $questions = Question::whereIn('topic_id', $topics)
        ->where('question_type', 'mcq')
        ->inRandomOrder()
        ->take($totalQuestions)
        ->with('mcqChoices')
        ->get();
      $totalQuestions = $questions->count();
      $shortQuestions = [];
    } elseif ($test_type === 'Conceptual') {
      $longQuestions = Question::where('question_nature', 'Conceptual')
        ->where('question_type', 'long')
        ->whereIn('topic_id', $topics)
        ->inRandomOrder()
        ->take($totalLongQuestions)
        ->with('answer')
        ->get();

      $shortQuestions = Question::where('question_nature', 'Conceptual')
        ->where('question_type', 'short')
        ->whereIn('topic_id', $topics)
        ->inRandomOrder()
        ->take($totalShortQuestions)
        ->with('answer')
        ->get();

      $questions = $longQuestions;
      $shortQuestions = $shortQuestions;
    } elseif ($test_type === 'Exercise') {
      $longQuestions = Question::where('question_nature', 'Exercise')
        ->where('question_type', 'long')
        ->whereIn('topic_id', $topics)
        ->inRandomOrder()
        ->take($totalLongQuestions)
        ->with('answer')
        ->get();

      $shortQuestions = Question::where('question_nature', 'Exercise')
        ->where('question_type', 'short')
        ->whereIn('topic_id', $topics)
        ->inRandomOrder()
        ->take($totalShortQuestions)
        ->with('answer')
        ->get();

      $questions = $longQuestions;
      $shortQuestions = $shortQuestions;
    } else {
      $longQuestions = Question::where('question_type', 'long')
        ->whereIn('topic_id', $topics)
        ->inRandomOrder()
        ->take($totalLongQuestions)
        ->with('answer')
        ->get();

      $shortQuestions = Question::where('question_type', 'short')
        ->whereIn('topic_id', $topics)
        ->inRandomOrder()
        ->take($totalShortQuestions)
        ->with('answer')
        ->get();

      $questions = $longQuestions;
      $shortQuestions = $shortQuestions;
    }

    // Retrieve random questions from the specified topics
    if ($test_type === 'Objective') {
      $view = 'syllabus-preparation.view-objective';
    } else {
      $view = 'syllabus-preparation.view-subjective';
    }

    $response = response()->view($view, [
      'test_type' => $test_type,
      'book_name' => $book->name,
      'totalQuestions' => $totalQuestions,
      'questions' => $questions,
      'shortQuestions' => $shortQuestions,
    ]);
    $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    $response->header('Pragma', 'no-cache');
    $response->header('Expires', '0');

    return $response;
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit()
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy()
  {
    //
  }

  public function keyPoints($bookId)
  {
    $board_id = Auth::user()->board_id;
    $class_id = Auth::user()->class_id;
    $book = Book::where('id', $bookId)->first('name');
    $questionTypes = QuestionType::where('type', '!=', 'long')
      ->where('type', '!=', 'short')
      ->get();
    $chapters = Chapter::where('board_id', $board_id)
      ->where('class_id', $class_id)
      ->where('book_id', $bookId)
      ->get();
    return view('syllabus-preparation.key-points', ['chapters' => $chapters, 'book' => $book->name, 'questionTypes' => $questionTypes]);
  }

  public function loadNotes($chapter, $questionType)
  {
    $questions = Question::with('answer')
      ->whereHas('topic', function ($query) use ($chapter) {
        $query->where('chapter_id', $chapter);
      })
      ->where('question_type', $questionType)
      ->get();
    if ($questions->isEmpty()) {
      return  $cols = '
      <div class="card mb-3">
  <div class="card-header">
  <h5 class="card-title">No Data Available.</h5>
  </div>
  </div>
  ';
    }
    $cols = '
<div class="card mb-3">

  <div class="card-body">';

    foreach ($questions as $question) {
      $cols .= ' <h5 class="card-title mt-3 mb-0">' . $question->description . ' </h5>';
      $cols .= ' <h6 class="card-title mb-3 mt-0">' . $question->answer['answer'] . ' </h6>';
    }

    $cols .= '
  </div>
</div>
';

    return $cols;
  }
}