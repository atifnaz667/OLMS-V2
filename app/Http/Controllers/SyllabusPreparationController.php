<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Topic;
use App\Models\Chapter;
use App\Models\Question;
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
        $query->selectRaw('MIN(id)')
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
    $questionType = $request->questionType;
    if ($questionType === 'Objective') {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_type', 'mcq');
      })->where('book_id', $bookId)->get();

      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_type', 'mcq')
        ->distinct()
        ->get();
    } elseif ($questionType === 'Conceptual') {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_nature', 'Conceptual');
      })->where('book_id', $bookId)->get();

      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_nature', 'Conceptual')
        ->distinct()
        ->get();
    } elseif ($questionType === 'Exercise') {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_nature', '!=', 'Exercise');
      })->where('book_id', $bookId)->get();

      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_nature', '!=', 'Exercise')
        ->distinct()
        ->get();
    } else {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_type', '!=', 'mcq');
      })->where('book_id', $bookId)->get();

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
    $totalLongQuestions = $request->totalLongQuestions;
    $totalShortQuestions = $request->totalShortQuestions;
    $topics = $request->topics;

    if ($test_type === 'Objective') {
      $questions = Question::whereIn('topic_id', $topics)
        ->where('question_type', 'mcq')
        ->inRandomOrder()
        ->take($totalQuestions)
        ->with('mcqChoices')
        ->get();
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

      $questions = $longQuestions->concat($shortQuestions);
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

      $questions = $longQuestions->concat($shortQuestions);
    } else {
      $questions = Question::whereIn('topic_id', $topics)
        ->where('question_type', '!=', 'mcq')
        ->inRandomOrder()
        ->take(2)
        ->with('answer')
        ->get();
    }

    // Retrieve random questions from the specified topics

    $response = response()->view('syllabus-preparation.view', [
      'test_type' => $test_type,
      'book_name' => $book->name,
      'totalQuestions' => $totalQuestions,
      'questions' => $questions
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
  public function update(Request $request,)
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
}
