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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/api', function () {
//    return 'test api';
//});

Route::post('api/index', 'Web\AuthController@index'); //test

Route::any('api/signup', 'Web\AuthController@signup');
Route::any('api/signin', 'Web\AuthController@signin');
Route::any('api/signout', 'Web\AuthController@signout');

// Question
Route::any('api/question/index', 'Web\QuestionController@index');
Route::any('api/question/show', 'Web\QuestionController@show');

Route::group(['middleware' => 'web_auth'], function () {
    Route::any('api/question/create', 'Web\QuestionController@create');
    Route::any('api/question/store', 'Web\QuestionController@store');
    Route::any('api/question/edit', 'Web\QuestionController@edit');
    Route::any('api/question/update', 'Web\QuestionController@update');
    Route::any('api/question/destroy', 'Web\QuestionController@destroy');
});


