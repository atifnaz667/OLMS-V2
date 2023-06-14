<?php

use App\Http\Controllers\AssignUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginController;
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
});


Route::middleware([AdminMiddleware::class])->group(function () {

  // --------------------------------------- Test Routes---------------------------------------
  Route::get('add-test', [TestController::class, 'store'])->name('add-test');


  // --------------------------------------- Assign user Routes---------------------------------------
  Route::post('assign-users', [AssignUserController::class, 'store']);
});


Route::middleware([StudentMiddleware::class])->group(function () {
  Route::get('syllabus-preparation', [SyllabusPreparationController::class, 'index']);
});


Route::middleware([ParentMiddleware::class])->group(function () {
  Route::get('test/create', [TestController::class, 'create']);
  Route::get('test/books', [TestController::class, 'getBooksForTest'])->name('test/books');
  Route::get('test/chapters', [TestController::class, 'getChaptersForTest'])->name('test/chapters');
  Route::post('test/store', [TestController::class, 'store'])->name('test/store');
  Route::get('test/list', [TestController::class, 'index'])->name('test/list');
  Route::get('fetchTestsRecords', [TestController::class, 'fetchTestsRecords'])->name('fetchTestsRecords');
});
