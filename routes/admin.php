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
