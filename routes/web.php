<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'QuestionController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions', 'QuestionController')->except('show');
Route::get('/questions/{slug}', 'QuestionController@show')->name('questions.show');
Route::patch('/questions/{question}/toggle-favourite', 'QuestionController@toggleFavourite')
    ->name('questions.toggleFavourite');

Route::resource('questions.answers', 'AnswerController')->except(['create', 'show']);

Route::post('answers/{answer}/accept', 'AcceptAnswerController')->name('answers.accept');

Route::patch('vote-for-question/{question}', 'VoteController@voteQuestion')->name('vote.question');
Route::patch('vote-for-answer/{answer}', 'VoteController@voteAnswer')->name('vote.answer');
