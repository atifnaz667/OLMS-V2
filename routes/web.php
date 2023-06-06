<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ChapterControlloer;
use App\Http\Controllers\QuestionController;


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

$controller_path = 'App\Http\Controllers';

// Main Page Route
Route::get('/', $controller_path . '\pages\HomePage@index')->name('pages-home');
Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');

// pages
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');


Route::resource('board', BoardController::class);


Route::resource('class', ClassesController::class);


Route::resource('book', BookController::class);
Route::get('getBoardBookClass', [BookController::class, 'getBoardBookClass']);


Route::resource('chapter', ChapterControlloer::class);
Route::get('/chapterDropDown', [ChapterControlloer::class, 'chapterDropDown'])->name('chapterDropDown');
Route::get('/fetchChapterRecords', [ChapterControlloer::class, 'getChapters'])->name('fetchChapterRecords');


Route::apiResource('topic', TopicController::class);
Route::get('topicDropDown', [TopicController::class, 'topics'])->name('topicDropDown');


Route::apiResource('question', QuestionController::class);
Route::get('add-question', [QuestionController::class, 'addQuestion'])->name('add-question');
