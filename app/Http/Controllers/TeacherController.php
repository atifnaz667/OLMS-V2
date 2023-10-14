<?php

namespace App\Http\Controllers;

use App\Helpers\DropdownHelper;
use App\Models\User;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use App\Models\AssignStudent;
use App\Models\AssignStudentChild;
use App\Models\AssignTeacherStudent;
use App\Models\Board;
use App\Models\Chapter;
use App\Models\Classes;
use App\Models\Comment;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestChild;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $rules = [
        'perPage' => 'integer|min:1',
        'sort_by' => 'in:id,id'
      ];
      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }
      try {
        $perPage = $request->input('perPage', 10);
        $sort = $request->input('sort_by', 'id');
        $sort_order = $request->input('sort_order', 'asc');
        $table = $request->input('table', 'assign_teacher_students');
        $sorting = $table . '.' . $sort;
        $teacher_id = $request->input('teacher_id');
        $book_id = $request->input('book_id');
        $class_id = $request->input('class_id');
        $board_id = $request->input('board_id');
        if ($request->check) {
          $assignedStudents = User::with(['assignUserAsStudent'=>function($q)use($book_id,$class_id,$board_id,$sorting, $sort_order){
            $q->when($book_id, function ($query) use ($book_id) {
              $query->where('book_id', $book_id);
          });
            $q ->when($class_id, function ($query) use ($class_id) {
               $query->whereHas('student', function ($query) use ($class_id) {
                   $query->where('class_id', $class_id);
                });
              });
              $q ->when($board_id, function ($query) use ($board_id) {
                $query->whereHas('student', function ($query) use ($board_id) {
                    $query->where('board_id', $board_id);
                 });
               });
               $q->orderBy($sorting, $sort_order);
          },'assignUserAsStudent.student.class','assignUserAsStudent.book'])
          ->find($request->teacher_id);

          // $assignedStudentsData = $assignedStudents->assignUserAsStudent()
          // ->orderBy($sorting, $sort_order)
          // ->paginate($perPage);
          // ->assignUserAsStudent()
          // ->orderBy($sorting, $sort_order)
          // ->paginate($perPage);
          // return $assignedStudents;
         $assignedStudentsData = $assignedStudents->assignUserAsStudent;
        $dataArray =[];
        foreach($assignedStudentsData as $studentData){
          $dataArray[] = [
                  'id' => $studentData->id,
                  'student_name' => $studentData->student->name,
                  'board' => $studentData->student->board->name,
                  'book' => $studentData->book->name,
                  'class' => $studentData->student->class->name
                 ];
          }
          // return response()->json($dataArray );
          // $data = $assignedStudents->assignUserAsStudent()->map(function ($student,$assignedStudents) {
          //   return [
          //     'id' => $assignedStudents->id,
          //     'name' => $student->name,
          //   ];
          // });
          // return $data;
          return response()->json([
            'status' => 'success',
            'message' => 'Assigned Students retrieved successfully',
            'data' => $dataArray,
            'current_page' => 1,
            'last_page' => 1,
            'per_page' => 10,
            'total' => 3,
          ]);
        }
        $teachers = User::where('role_id',3)->get();
        $results = DropdownHelper::getBoardBookClass();
        $classes = $results['Classes'];
        $boards = $results['Boards'];
        $books = $results['Books'];
  
        return view('assign-students.index', ['teachers' => $teachers, 'classes' => $classes, 'boards' => $boards,'books'=>$books]);
      } catch (\Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);
  
        return back()->with('error', $message);
      }
    }
    public function assignStudents()
    {
        $teachers = User::where('role_id',3)->get();
        $results = DropdownHelper::getBoardBookClass();
        $classes = $results['Classes'];
        $boards = $results['Boards'];
        $books = $results['Books'];
        return view('assign-students.add', ['teachers' => $teachers, 'classes' => $classes, 'boards' => $boards,'books'=>$books]);
    }
    public function getStudents(Request $request)
    {
        $rules = array(
            'board_id' => 'required|int|exists:boards,id',
            'class_id' => 'required|int|exists:classes,id'
          );
          $validator = Validator::make($request->all(), $rules);
          if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
          }
          try {
            $getStudents = User::where('role_id', 4)
              ->where('board_id', $request->board_id)
              ->where('class_id', $request->class_id)
              ->select('id', 'name')
              ->whereNotIn('id', function ($query) use ($request) {
                  $query->select('student_id')
                      ->from('assign_teacher_students')
                      ->where('book_id', $request->book_id)
                      ->where('teacher_id', $request->teacher_id);
              })
              ->get();

            $students = '<option value="">Select Students</option>';

            foreach ($getStudents as $student) {
              $students = $students . '<option value="' . $student->id . '">' . $student->name . '</option>';
            }
            return response()->json(['status' => 'success', 'students' => $students], 200);
            } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }

    }
    /**getStudents
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
        $rules = array(
            'teacher_id' => 'required',
            'book_id' => 'required',
            'board_id' => 'required',
            'students' => 'required',
            'class_id' => 'required',
          );
          $validator = Validator::make($request->all(), $rules);
          if ($validator->fails()) {
            return back()->with(['status' => 'error', 'message' => $validator->errors()->first()], 400);
          }
          $board_id = $request->board_id;
          $class_id = $request->class_id;
          foreach ($request->students as $studentId) {
            $chickStudent = AssignTeacherStudent::with('student')
            ->where('teacher_id', $request->teacher_id)
            ->where('student_id', $studentId)
            ->where('book_id', $request->book_id)
            ->whereHas('student', function ($query) use ($board_id,$class_id) {
              $query->where('board_id', $board_id);
              $query->where('class_id', $class_id);
            })
            ->first();
            if ($chickStudent) {
              return response()->json([
                    'status' => 'error',
                    'message' => 'Students Already Assigned',
                  ], 400);
              }
              break;
          }
          try {
                DB::beginTransaction();
                $students = $request->students;
                foreach ($students as $studentId) {
                    $assignStudentChild = new AssignTeacherStudent();
                    $assignStudentChild->teacher_id = $request->teacher_id;
                    $assignStudentChild->student_id = $studentId;
                    $assignStudentChild->book_id = $request->book_id;
                    $assignStudentChild->save();
                }
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Students Assigned  successfully',
                  ], 201);
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
     * Display the Teacher test List.
     */
    public function teacherTestList()
    {
      return view('test.teacher-test.list');
    }

    /**
     * Create teacher test for students.
     */
    public function teacherCreateTest()
    {
      $teacher_id = Auth::user()->id;
      $boards = Board::orderBy('name', 'asc')->get();
      $classes = Classes::orderBy('name', 'asc')->get();
      $timeOptions = Helpers::getTimeForQuestions();
      $bookData = AssignTeacherStudent::with(['book' => function ($query) {
            $query->select('id', 'name');
        }])
        ->where('teacher_id', $teacher_id)
        ->get();
        $uniqueBooks = $bookData->unique('book.id');
        $books =[];
        foreach($uniqueBooks as $book){
          $books[] = [
                  'id' => $book->book->id,
                  'name' => $book->book->name,
                 ];
          }
          // return $classes;
      return view('test.teacher-test.add', ['timeOptions' => $timeOptions, 'boards' => $boards, 'classes' => $classes,'books'=>$books]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function getChaptersForTest(Request $req)
    {
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query
          ->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_type', '!=', 'mcq');
      })
        ->where('book_id', $req->book_id)
        ->where('board_id', $req->board_id)
        ->where('class_id', $req->class_id)
        ->get();
  
      $cols = '<div class="col-12 mb-2">
               <input class="form-check-input" style="margin-right:1em" id="select-all" onclick="selectCheckboxes()" type="checkBox"> Select All
           </div>';
  
      foreach ($chapters as $chapter) {
        $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
          ->select('topics.*')
          ->where('topics.chapter_id', $chapter->id)
          ->where('questions.question_type', '!=', 'mcq')
          ->distinct()
          ->get();
  
        $cols .= '<div class="col-sm-12 mb-3">
                   <input style="margin-right:1em" onclick="selectCheckbox()" type="checkBox" name="chapters[]" class="form-check-input checkboxes" value="' . $chapter->id . '"> <strong>Unit: ' . $chapter->name . '</strong>
               </div>';
  
        $topicsInRow = 0;
        $cols .= '<div class="col-sm-12">';
        foreach ($topics as $topic) {
          if ($topicsInRow % 3 === 0 && $topicsInRow !== 0) {
            $cols .= '</div><div class="col-sm-12">';
          }
          $cols .= '<div class="col-sm-4 mb-2" style="display: inline-block; margin-right: 10px;">
                       <input style="margin-right:1em" type="checkBox" name="topics[]" class="form-check-input checkboxes" value="' . $topic->id . '"> ' . $topic->name . '
                   </div>';
          $topicsInRow++;
        }
        $cols .= '</div>';
      }
  
      if (
        count($chapters) == 0
      ) {
        $cols = '<div class="col-12"> <h6>No Chapters found against this book </h6></div>';
      }
      if (!$req->book_id) {
        $cols = '<div class="col-12"> <h6>Please select a book </h6></div>';
      }
  
      return $cols;
  
  
      $chapters = Chapter::whereHas('topics', function ($query) {
        $query
          ->join('questions', 'questions.topic_id', '=', 'topics.id')
          ->where('questions.question_type', '!=', 'mcq');
      })
        ->where('book_id', $req->book_id)
        ->where('board_id', $req->board_id)
        ->where('class_id', $req->class_id)
        ->get();
      $topics = Topic::join('questions', 'questions.topic_id', '=', 'topics.id')
        ->select('topics.*')
        ->whereIn('topics.chapter_id', $chapters->pluck('id'))
        ->where('questions.question_type', '!=', 'mcq')
        ->distinct()
        ->get();
      $cols = '';
      $cols = '<div class="col-12 mb-2">
      <input class="form-check-input" style="margin-right: 1em" id="select-all" onclick="selectCheckboxes()" type="checkbox"> Select All
     </div>';
  
      foreach ($chapters as $chapter) {
        $cols .= '<div class="mb-2 p-2">
          <div class="form-check">
              <input class="form-check-input chapter-checkbox" onclick="selectCheckbox()" type="checkbox" id="chapter_' . $chapter->id . '">
              <input type="hidden" id="book_id" value="' . $chapter->book_id . '">
              <h5 class="form-check-h5" for="chapter_' . $chapter->id . '">' . $chapter->name . '</h5>
          </div>
          <div id="topicList_' . $chapter->id . '" class="row mb-4">';
  
        foreach ($topics as $topic) {
          if ($topic->chapter_id === $chapter->id) {
            $cols .= '<div class="col-sm-4">
                  <div class="form-check">
                      <input class="form-check-input topic-checkbox" type="checkbox" id="topic_' . $topic->id . '">
                      <h6 class="form-check-h6" for="topic_' . $topic->id . '">' . $topic->name . '</h6>
                  </div>
              </div>';
          }
        }
  
        $cols .= '</div></div>';
      }
  
      if (count($chapters) == 0) {
        $cols = '<div class="col-12"> <h6>No Chapters or Topics found against this book </h6></div>';
      }
      if (!$req->book_id) {
        $cols = '<div class="col-12"> <h6>Please select a book </h6></div>';
      }
  
      return $cols;
    }

    /**
     * get Teacher Assign Students.
     */
    // public function getTeacherAssignStudents(Request $request)
    // {
    //   $bookData = AssignTeacherStudent::with(['book' => function ($query) {
    //     $query->select('id', 'name');
    // }])
    // ->where('teacher_id', $teacher_id)
    // ->get();
    // }
    public function getTeacherAssignStudents(Request $request)
    {
        $rules = array(
            'board_id' => 'required|int|exists:boards,id',
            'class_id' => 'required|int|exists:classes,id',
            'book_id' => 'required|int|exists:books,id'
          );
          $validator = Validator::make($request->all(), $rules);
          if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
          }
          try {
            $teacher_id = Auth::user()->id;
            $getStudents = User::where('role_id', 4)
              ->where('board_id', $request->board_id)
              ->where('class_id', $request->class_id)
              ->select('id', 'name')
              ->whereIn('id', function ($query) use ($request,$teacher_id) {
                  $query->select('student_id')
                      ->from('assign_teacher_students')
                      ->where('book_id', $request->book_id)
                      ->where('teacher_id', $teacher_id);
              })
              ->get();

            $students = '<option value="">Select Students</option>';

            foreach ($getStudents as $student) {
              $students = $students . '<option value="' . $student->id . '">' . $student->name . '</option>';
            }
            return response()->json(['status' => 'success', 'students' => $students], 200);
            } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }

    }
    public function teacherStoreTest(Request $request)
  {
    // return $request;
    $rules = array(
      'testDate' => 'required',
      'totalQuestions' => 'required|int|max:100',
      'chapters' => 'required',
      'book' => 'required',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->with(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
      DB::beginTransaction();
      $createdBy = Auth::user()->id;
      $testDate = $request->testDate ?? Date('Y-m-d');
      $totalQuestions = $request->totalQuestions ?? 10;
      $chapters = $request->chapters;
      $topics = $request->topics;
      $book = $request->book;
      $questionTime = $request->questionTime;
      $users = $request->students;
      if (count($users) == 0) {
        return back()->with(['status' => 'error', 'message' => 'Students not found'], 422);
      }
      foreach ($users as $user) {
        $storeTest = $this->storeTest($topics, $totalQuestions, $createdBy, $user, $testDate, $questionTime, $book);
        if (!$storeTest) {
          DB::rollBack();
          return back()->with(['status' => 'error', 'message' => 'Questions not found against these chapters'], 422);
        }
      }
      DB::commit();
      return redirect('teacher/test/list')->with(['status' => 'success', 'message' => 'Test created successfully'], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      $message = CustomErrorMessages::getCustomMessage($e);
      return back()->with(['status' => 'error', 'message' => $message], 500);
    }
  }
  public function storeTest($topics, $totalQuestions, $createdBy, $user, $testDate, $questionTime, $book)
  {
    $student = User::find($user);
    // $topics = Topic::whereIn('chapter_id', $chapters)->get()->pluck('id');
    $questions = Question::inRandomOrder()->where('question_type', 'mcq')->whereIn('topic_id', $topics)->limit($totalQuestions)->get();
    if (count($questions) == 0) {
      return false;
    }
    $test = new Test();
    $test->created_for = $student->id;
    $test->created_by = $createdBy;
    $test->status = 'Pending';
    $test->test_date = $testDate;
    $test->test_type = 'Monthly';
    $test->question_time = $questionTime;
    $test->total_questions = count($questions);
    $test->book_id = $book;
    $test->class_id = $student->class_id;
    $test->board_id = $student->board_id;
    $test->save();
    foreach ($questions as $question) {
      $testChild = new TestChild();
      $testChild->test_id = $test->id;
      $testChild->question_id = $question->id;
      $testChild->save();
    }
    return true;
  }

  public function fetchTestsRecords(Request $request)
  {
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:date,id'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      $perPage = $request->input('perPage', 10);
      $sort = $request->input('sort_by', 'id');
      $sort_order = $request->input('sort_order', 'asc');
      $table = $request->input('table', 'tests');
      $sorting = $table . '.' . $sort;

      $from = $request->input('from');
      $to = $request->input('to');
      $status = $request->input('status');

      $user_id = Auth::user()->id;
      $tests = Test::withCount('obtainedMarks')
        ->where('created_by', $user_id)
        ->when($from, function ($query) use ($from) {
          $query->where('created_at', '>', $from . " 00:00:00");
        })
        ->when($to, function ($query) use ($to) {
          $query->where('created_at', '<', $to . " 23:59:59");
        })
        ->when($status, function ($query) use ($status) {
          $query->where('status', $status);
        })
        ->orderBy($sorting, $sort_order)->paginate($perPage);
      $data = $tests->map(function ($tests) {

        if ($tests->expiry_date != null && $tests->expiry_date < date("Y-m-d")) {
          $status = '<span class="badge rounded bg-label-danger">Expired</span>';
        } elseif ($tests->status == 'Pending') {
          $status = '<span class="badge rounded bg-label-warning">' . $tests->status . '</span>';
        } elseif ($tests->status == 'Attempted') {
          $status = '<span class="badge rounded bg-label-success">' . $tests->status . '</span>';
        }
        return [
          'id' => $tests->id,
          'user' => Auth::user()->role_id == 4 ? $tests->createdBy->name : $tests->createdFor->name,
          'book' => $tests->book->name,
          'status' =>  $status,
          'status2' =>  $tests->status,
          'obtained_marks' => $tests->status == 'Attempted' ? $tests->obtained_marks_count : 0,
          'total_marks' => $tests->total_questions,
          'test_type' => $tests->test_type,
          'attempted_at' => Helpers::formatDate($tests->attempted_at),
          'test_date' => Helpers::formatDate($tests->test_date),
          'expiry_date' => Helpers::formatDate($tests->expiry_date),
          'created_at' => Helpers::formatDate($tests->created_at),
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Tests retrieved successfully',
        'data' => $data,
        'current_page' => $tests->currentPage(),
        'last_page' => $tests->lastPage(),
        'per_page' => $tests->perPage(),
        'total' => $tests->total(),
      ]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

  public function teacherAssignedStudents(Request $request){
    $rules = [
 
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }
    try {
      $book_id = $request->input('book_id');
      $class_id = $request->input('class_id');
      $board_id = $request->input('board_id');
      $teacher_id = Auth::user()->id;
      $students= AssignTeacherStudent::with('student.class','student.board','book')
      ->when($book_id, function ($query, $book_id) {
        return $query->where('book_id', $book_id);
      })->where('teacher_id',$teacher_id)->get();
 
      $teachers = User::where('role_id',3)->get();
      $results = DropdownHelper::getBoardBookClass();
      $classes = $results['Classes'];
      $boards = $results['Boards'];
      $books = $results['Books'];

      return view('teacher.assign-students-list', ['students' => $students, 'classes' => $classes, 'boards' => $boards,'books'=>$books]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return back()->with('error', $message);
    }
  }
  public function fetchAssignedStudentRecords(Request $request)
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
      $table = $request->input('table', 'assign_teacher_students');
      $sorting = $table . '.' . $sort;

      $board = $request->input('board_id');
      $book = $request->input('book_id');
      $class = $request->input('class_id');

      $students = AssignTeacherStudent::with('student.class','student.board','book')
        ->when($board, function ($query) use ($board) {
          $query->whereHas('student', function ($query) use ($board) {
            $query->whereHas('board', function ($query) use ($board) {
            $query->where('id', $board);
            });
          });
        })
        ->when($book, function ($query) use ($book) {
          $query->whereHas('book', function ($query) use ($book) {
            $query->where('id', $book);
          });
        })
        ->when($class, function ($query) use ($class) {
          $query->whereHas('student', function ($query) use ($class) {
           $query->whereHas('class', function ($query) use ($class) {
            $query->where('id', $class);
            });
          });
        })
        ->orderBy($sorting, $sort_order)->paginate($perPage);

        // $students= AssignTeacherStudent::with('student.class','student.board','book')
        // ->when($book_id, function ($query, $book_id) {
        //   return $query->where('book_id', $book_id);
        // })->where('teacher_id',$teacher_id)->get();
      $data = $students->map(function ($student) {
        return [
          'id' => $student->id,
          'student_name' => $student->student->name,
          'board' => $student->student->board->name,
          'book' => $student->book->name,
          'class' => $student->student->class->name,
          'student_id' => $student->student_id,     
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Students retrieved successfully',
        'data' => $data,
        'current_page' => $students->currentPage(),
        'last_page' => $students->lastPage(),
        'per_page' => $students->perPage(),
        'total' => $students->total(),
      ]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }
  public function saveComment(Request $request){
    
    $rules = array(
      'student_id' => 'required',
      'comment' => 'required',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->with(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
  
    $teacher_id = Auth::user()->id;

    try {

        $comment = new Comment();
        $comment->teacher_id = $teacher_id;
        $comment->student_id	 = $request->student_id;
        $comment->comment = $request->comment;
        $comment->save();

        // Return success status and message
        return response()->json([
          'status' => 'success',
          'message' => 'Comment stored successfully.',
        ], 201);
      } catch (\Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);
        dd($message);
        // Return error status and message
        return response()->json([
          'status' => 'error',
          'message' => 'Failed to store Comment.',
        ], 500);
      }

  }
  public function showComments(Request $request)
{
    $teacher_id = Auth::user()->id;
    $rows = '';

    try {
        $comments = Comment::where('student_id', $request->student_id)
            ->where('teacher_id', $teacher_id)
            ->get();
           $count =1;
        foreach ($comments as $comment) {
            $rows .= '<tr>';
            $rows .= '<td>' . $count++ . '</td>';
            $rows .= '<td>' . $comment->created_at . '</td>';
            $rows .= '<td>' . $comment->comment . '</td>';
            $rows .= '<td onclick="openEditCommentModal(' . $comment->id . ')"><i class="ti ti-edit ti-sm me-2" aria-hidden="true"></i></td>';
            $rows .= '</tr>';
        }

        return response()->json([
            'data' => $rows,
        ]);

    } catch (\Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);

        return back()->with('error', $message);
    }
 }
 public function editComment(Request $request){

  try {
      $comment = Comment::where('id', $request->comment_id)
          ->first(); 

      return response()->json([
          'commentText' => $comment->comment,
      ]);

  } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return back()->with('error', $message);
  }
 }
 public function updateComment(Request $request){
    
  $rules = array(
    'editCommentId' => 'required',
    'editCommentValue' => 'required',
  );
  $validator = Validator::make($request->all(), $rules);
  if ($validator->fails()) {
    return back()->with(['status' => 'error', 'message' => $validator->errors()->first()], 400);
  }
  try {

      $comment =  Comment::find($request->editCommentId);
      $comment->comment = $request->editCommentValue;
      $comment->save();

      // Return success status and message
      return response()->json([
        'status' => 'success',
        'message' => 'Comment Update successfully.',
      ], 201);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      dd($message);
      // Return error status and message
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to store Comment.',
      ], 500);
    }
  }
}
