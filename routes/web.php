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
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\SyllabusPreparationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssignRoleController;
use App\Http\Controllers\NoteController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CommonRoutes;
use App\Http\Middleware\StaffMiddleware;
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
  return 'Cleared!';
});

Route::middleware([CommonRoutes::class])->group(function () {
  Route::get('/home', [HomePage::class, 'index'])->name('pages-home');
  Route::get('getGraphDataAjax', [HomePage::class, 'getGraphDataAjax'])->name('getGraphDataAjax');
  Route::get('fetch-chapters-topics/{id}', [SyllabusPreparationController::class, 'fetchData'])->name(
    'fetch-chapters-topics'
  );
  Route::post('get-test-for-preparation', [SyllabusPreparationController::class, 'show'])->name(
    'get-test-for-preparation'
  );
  Route::get('/calculator', function () {
    return view('users.calculator');
  })->name('calculator');

  Route::get('/add-notes', function () {
    return view('notes.add');
  })->name('add-notes');

  Route::post('/store-note', [UserController::class, 'storeNote'])->name('store-note');
  //Route::post('/update-note', [UserController::class, 'updateNote'])->name('update-note');
  Route::get('/view-note/{id}', [UserController::class, 'viewNote'])->name('viewNote');
  Route::get('notes/show/{id}', [NoteController::class, 'show'])->name('notes.show');
  Route::get('/notes', [UserController::class, 'notes'])->name('notes');
  Route::put('notes/update/{id}', [UserController::class, 'updateNote'])->name('notes.update');
  Route::get('/delete-note/{id}', [UserController::class, 'deleteNote'])->name('deleteNote');

  Route::get('edit-user', [UserController::class, 'editUser'])->name('edit-userr');
  Route::get('detail-user/{id}', [UserController::class, 'details']);
  Route::post('update-user-info', [UserController::class, 'updateUserInfo'])->name('update-user-info');

  Route::resource('note', NoteController::class);

});
Route::middleware([AdminMiddleware::class])->group(function () {
  // Main Page Route
  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  // pages
  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

  Route::resource('board', BoardController::class);
  Route::resource('questionType', QuestionTypeController::class);

  Route::resource('class', ClassesController::class);

  Route::resource('book', BookController::class);
  Route::get('getBoardBookClass', [BookController::class, 'getBoardBookClass']);

  Route::resource('chapter', ChapterControlloer::class);
  Route::get('/chapterDropDown', [ChapterControlloer::class, 'chapterDropDown'])->name('chapterDropDown');
  Route::get('/fetchChapterRecords', [ChapterControlloer::class, 'getChapters'])->name('fetchChapterRecords');
  Route::get('add-chapter', [ChapterControlloer::class, 'addChapter'])->name('add-chapter');
  Route::get('topicDropDown', [TopicController::class, 'topics'])->name('topicDropDown');

  Route::apiResource('topic', TopicController::class);
  Route::get('add-topic', [TopicController::class, 'addTopic'])->name('add-topic');

  Route::apiResource('question', QuestionController::class);
  Route::get('add-question', [QuestionController::class, 'addQuestion'])->name('add-question');

  Route::apiResource('mcq-choice', McqChoiceController::class);
  Route::get('add-mcq-choice', [McqChoiceController::class, 'addMcqChoioce'])->name('add-mcq-choice');
  Route::get('mcq-choice-details/{id}', [McqChoiceController::class, 'McqChoioceDetails'])->name('mcq-choice-details');


  Route::get('get-dropdown-for-assign', [UserController::class, 'getDropDown'])->name('get-dropdown-for-assign');
  Route::post('assign-user', [UserController::class, 'assignUser'])->name('assign-user');

  Route::resource('assignRole', AssignRoleController::class);
  Route::get('edit-assignRole', [AssignRoleController::class, 'editUser'])->name('edit-assignRole');
});

Route::middleware([StaffMiddleware::class])->group(function () {
  // Main Page Route
  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  // pages

  Route::apiResource('mcq-choice', McqChoiceController::class);
  Route::get('add-mcq-choice', [McqChoiceController::class, 'addMcqChoioce'])->name('add-mcq-choice');
  Route::get('/chapterDropDown', [ChapterControlloer::class, 'chapterDropDown'])->name('chapterDropDown');
  Route::get('topicDropDown', [TopicController::class, 'topics'])->name('topicDropDown');

  Route::apiResource('question', QuestionController::class);
  Route::get('add-question', [QuestionController::class, 'addQuestion'])->name('add-question');

  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
});
