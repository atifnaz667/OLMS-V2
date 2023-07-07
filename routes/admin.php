<?php

use App\Http\Controllers\AdminTestController;
use App\Http\Controllers\AssignUserController;
use App\Http\Controllers\AttemptTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginController;
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Controllers\SyllabusPreparationController;
use App\Http\Controllers\TestController;

$controller_path = 'App\Http\Controllers';

// --------------------------------------- Auth Routes---------------------------------------

Route::middleware([AlreadyLoggedIn::class])->group(function () {
  Route::get('/', [LoginController::class, 'index']);
  Route::post('login', [LoginController::class, 'login']);
});

//------------------------------Common Routes--------------------------
Route::middleware([CommonRoutes::class])->group(function () {
  Route::get('logout', [LoginController::class, 'logout'])->name('logout');
  Route::get('test/list', [TestController::class, 'index'])->name('test/list');
  Route::get('fetchTestsRecords', [TestController::class, 'fetchTestsRecords'])->name('fetchTestsRecords');
  Route::get('test/result', [TestController::class, 'getTestResult'])->name('test/result');
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
  Route::get('fetchTestsRecordsAdmin', [AdminTestController::class, 'fetchTestsRecords'])->name('fetchTestsRecordsAdmin');



  // --------------------------------------- Assign user Routes---------------------------------------
  Route::post('assign-users', [AssignUserController::class, 'store']);
});

Route::middleware([StudentMiddleware::class])->group(function () {
  Route::get('syllabus-preparation', [SyllabusPreparationController::class, 'index'])->name('syllabus-preparation');

  //----------------------------Attempt Test ROutes--------------------------------
  Route::get('tests', [AttemptTestController::class, 'index']);
  Route::post('test/instructions', [AttemptTestController::class, 'create'])->name('test/instructions');
  Route::post('test/attempt', [AttemptTestController::class, 'show'])->name('test/attempt');
  Route::post('attempt-test-ajax', [AttemptTestController::class, 'attemptTestAjax'])->name('attempt-test-ajax');
  Route::post('store-test-answer', [AttemptTestController::class, 'store'])->name('store-test-answer');

  //---------------------------------Self Assessment routes-------------------
  Route::get('self/assessment', [SelfAssessmentController::class, 'create'])->name('self/assessment');
  Route::post('self/assessment', [SelfAssessmentController::class, 'store'])->name('self/assessment');
  Route::get('self/assessment/chapters', [SelfAssessmentController::class, 'getChaptersForTest'])->name('self/assessment/chapters');


});

Route::middleware([ParentMiddleware::class])->group(function () {
  Route::get('test/create', [TestController::class, 'create'])->name('test/create');
  Route::get('test/books', [TestController::class, 'getBooksForTest'])->name('test/books');
  Route::get('test/chapters', [TestController::class, 'getChaptersForTest'])->name('test/chapters');
  Route::post('test/store', [TestController::class, 'store'])->name('test/store');
});
