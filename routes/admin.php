<?php

use App\Http\Controllers\AdminTestController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignUserController;
use App\Http\Controllers\AttemptTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginController;
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\SyllabusPreparationController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\VisualController;

$controller_path = 'App\Http\Controllers';

// --------------------------------------- Auth Routes---------------------------------------

Route::middleware([AlreadyLoggedIn::class])->group(function () {
  Route::get('/', [LoginController::class, 'index']);
  Route::get('/login/{type}', [LoginController::class, 'show']);
  Route::get('/signup/{type}', [LoginController::class, 'sigupPage']);
  Route::post('signup', [LoginController::class, 'sigup']);
  Route::post('login', [LoginController::class, 'login']);
});
Route::post('store-pending-user', [LoginController::class, 'storePendingUser']);
Route::get('pending-user', [LoginController::class, 'pendingUser'])->name('pending-user');

//------------------------------Common Routes--------------------------
Route::middleware([CommonRoutes::class])->group(function () {
  Route::get('logout', [LoginController::class, 'logout'])->name('logout');
  Route::get('test/list', [TestController::class, 'index'])->name('test/list');
  Route::get('fetchTestsRecords', [TestController::class, 'fetchTestsRecords'])->name('fetchTestsRecords');
  Route::get('test/result', [TestController::class, 'getTestResult'])->name('test/result');
  Route::get('suggestion/create', [SuggestionController::class, 'create'])->name('suggestion/create');
  Route::post('suggestion/store', [SuggestionController::class, 'store'])->name('suggestion/store');
  Route::get('announcement/show/{id}', [AnnouncementController::class, 'show'])->name('announcement.show');

});

Route::middleware([AdminMiddleware::class])->group(function () {
  // --------------------------------------- Test Routes---------------------------------------
  Route::get('add-test', [TestController::class, 'store'])->name('add-test');

  // ---------------------------------------Create Test Routes---------------------------------------
  Route::get('admin/create/test', [AdminTestController::class, 'create'])->name('admin/create/test');
  Route::get('admin/test/list', [AdminTestController::class, 'index'])->name('admin/test/list');
  Route::get('get/books/ajax', [AdminTestController::class, 'getBooksAjax'])->name('get/books/ajax');
  Route::get('admin/test/chapters', [AdminTestController::class, 'getChaptersForTest'])->name('admin/test/chapters');
  Route::get('admin/test/students', [AdminTestController::class, 'getStudentsForTest'])->name('admin/test/students');
  Route::post('admin/store/test', [AdminTestController::class, 'store'])->name('admin/store/test');
  Route::get('fetchTestsRecordsAdmin', [AdminTestController::class, 'fetchTestsRecords'])->name(
    'fetchTestsRecordsAdmin'
  );

  //---------------------------------------------Suggestion Routes------------------------------------
  Route::get('suggestion/list', [SuggestionController::class, 'index'])->name('suggestion/list');
  Route::get('fetchSuggestionRecords', [SuggestionController::class, 'getSuggestions'])->name('fetchSuggestionRecords');
  Route::get('suggestion/destroy/{id}', [SuggestionController::class, 'destroy'])->name('suggestion.destroy');

  // --------------------------------------- Assign user Routes---------------------------------------
  Route::post('assign-users', [AssignUserController::class, 'store']);


  // --------------------------------------- Visuals Routes---------------------------------------
  Route::get('visuals', [VisualController::class, 'index'])->name('visual.index');
  Route::get('visuals/create', [VisualController::class, 'create'])->name('visual.create');
  Route::post('add-visual', [VisualController::class, 'store'])->name('add-visual');
  Route::get('visual/show/{id}', [VisualController::class, 'show'])->name('visual.show');
  Route::put('visual/update', [VisualController::class, 'update'])->name('visual.update');
  Route::delete('visual/destroy/{id}', [VisualController::class, 'destroy'])->name('visual.destroy');



  // --------------------------------------- Teacher Route---------------------------------------
  Route::get('assigned/students', [TeacherController::class, 'index'])->name('assigned/students');
  Route::get('assign/students', [TeacherController::class, 'assignStudents'])->name('assign/students');
  Route::get('get/students/ajax', [TeacherController::class, 'getStudents'])->name('get/students/ajax');
  Route::post('assignStudent/store', [TeacherController::class, 'store'])->name('assignStudent/store');
});

Route::middleware([StudentMiddleware::class])->group(function () {
  Route::get('syllabus-preparation', [SyllabusPreparationController::class, 'index'])->name('syllabus-preparation');
  Route::get('keyPoints/{bookId}', [SyllabusPreparationController::class, 'keyPoints']);
  Route::get('load-notes/{chapter}/{questionType}', [SyllabusPreparationController::class, 'loadNotes']);

  //----------------------------Attempt Test ROutes--------------------------------
  Route::get('tests', [AttemptTestController::class, 'index']);
  Route::post('test/instructions', [AttemptTestController::class, 'create'])->name('test/instructions');
  Route::post('test/attempt', [AttemptTestController::class, 'show'])->name('test/attempt');
  Route::post('attempt-test-ajax', [AttemptTestController::class, 'attemptTestAjax'])->name('attempt-test-ajax');
  Route::post('store-test-answer', [AttemptTestController::class, 'store'])->name('store-test-answer');

  //---------------------------------Self Assessment routes-------------------
  Route::get('self/assessment', [SelfAssessmentController::class, 'create'])->name('self/assessment');
  Route::post('self/assessment', [SelfAssessmentController::class, 'store'])->name('self/assessment');
  Route::get('self/assessment/chapters', [SelfAssessmentController::class, 'getChaptersForTest'])->name(
    'self/assessment/chapters'
  );

  Route::post('get/visuals', [VisualController::class, 'getVisualsForStudent'])->name('get.visuals');
  Route::post('get/visuals/ajax', [VisualController::class, 'getVisualsForStudentAjax'])->name('get.visuals.ajax');

  Route::get('notice/board.ajax', [AnnouncementController::class, 'noticeBoard'])->name('notice.board.ajax');
    //---------------------------------Student teacher and comment list routes-------------------
    Route::get('myComment/list', [StudentController::class, 'getStudentComment'])->name('myComment/list');
    Route::get('myTeacher/list', [StudentController::class, 'myTeacherList'])->name('myTeacher/list');
});

Route::middleware([ParentMiddleware::class])->group(function () {
  Route::get('test/create', [TestController::class, 'create'])->name('test/create');
  Route::get('test/books', [TestController::class, 'getBooksForTest'])->name('test/books');
  Route::get('test/chapters', [TestController::class, 'getChaptersForTest'])->name('test/chapters');
  Route::post('test/store', [TestController::class, 'store'])->name('test/store');
});

Route::middleware([TeacherMiddleware::class])->group(function () {
  Route::get('teacher/create/test', [TeacherController::class, 'teacherCreateTest'])->name('teacher/create/test');
  Route::get('teacher/test/list', [TeacherController::class, 'teacherTestList'])->name('teacher/test/list');
  Route::get('teacher/test/chapters', [TeacherController::class, 'getChaptersForTest'])->name('teacher/test/chapters');
  Route::get('teacher/test/students', [TeacherController::class, 'getTeacherAssignStudents'])->name('teacher/test/students');
  Route::post('teacher/store/test',[TeacherController::class,'teacherStoreTest'])->name('teacher/store/test');
  Route::get('fetchTestsRecordsTeacher', [TeacherController::class, 'fetchTestsRecords'])->name('fetchTestsRecordsTeacher');

    // --------------------------------------- Announcements Routes---------------------------------------
    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcement.index');
    Route::get('announcements/ajax', [AnnouncementController::class, 'announcementsAjax'])->name('announcement.ajax');
    Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcement.create');
    Route::post('add-announcement', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::put('announcement/update/{id}', [AnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('announcement/destroy/{id}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');
  Route::get('teacherAssignedStudents/list', [TeacherController::class, 'teacherAssignedStudents'])->name('teacherAssignedStudents/list');
  Route::get('fetchAssignedStudentRecords', [TeacherController::class, 'fetchAssignedStudentRecords'])->name('fetchAssignedStudentRecords');
  Route::post('saveComment',[TeacherController::class,'saveComment'])->name('saveComment');
  Route::get('showComments', [TeacherController::class, 'showComments'])->name('showComments');
  Route::get('editComment', [TeacherController::class, 'editComment'])->name('editComment');
  Route::post('updateComment',[TeacherController::class,'updateComment'])->name('updateComment');
});
