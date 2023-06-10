<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ChapterControlloer;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\McqChoiceController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/us-clear', function () {

  Artisan::call('view:clear');
  Artisan::call('route:clear');
  Artisan::call('config:clear');
  Artisan::call('cache:clear');
  // Artisan::call('config:cache');
  return "Cleared!";
});


Route::middleware([CommonRoutes::class])->group(function () {

  Route::get('/home', [HomePage::class, 'index'])->name('pages-home');
});
Route::middleware([AdminMiddleware::class])->group(function () {
  // Main Page Route
  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  // pages
  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

  Route::resource('board', BoardController::class);


  Route::resource('class', ClassesController::class);


  Route::resource('book', BookController::class);
  Route::get('getBoardBookClass', [BookController::class, 'getBoardBookClass']);


  Route::resource('chapter', ChapterControlloer::class);
  Route::get('/chapterDropDown', [ChapterControlloer::class, 'chapterDropDown'])->name('chapterDropDown');
  Route::get('/fetchChapterRecords', [ChapterControlloer::class, 'getChapters'])->name('fetchChapterRecords');
  Route::get('add-chapter', [ChapterControlloer::class, 'addChapter'])->name('add-chapter');


  Route::apiResource('topic', TopicController::class);
  Route::get('topicDropDown', [TopicController::class, 'topics'])->name('topicDropDown');
  Route::get('add-topic', [TopicController::class, 'addTopic'])->name('add-topic');


  Route::apiResource('question', QuestionController::class);
  Route::get('add-question', [QuestionController::class, 'addQuestion'])->name('add-question');


  Route::apiResource('mcq-choice', McqChoiceController::class);
  Route::get('add-mcq-choice', [McqChoiceController::class, 'addMcqChoioce'])->name('add-mcq-choice');
});
