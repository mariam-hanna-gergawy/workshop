<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::post('/register', 'UserController@store');
Route::post('/login', 'UserController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('/tweets', 'TweetController@store');
    Route::get('/tweets', 'TweetController@index');
    Route::delete('/tweets/{tweet}', 'TweetController@destroy')->where('tweet', '[0-9]+')->middleware('can:destroy-tweet,tweet');
    Route::post('/users/{user}/follow', 'UserController@follow')->where('user', '[0-9]+')->middleware('can:follow-user,user');
});


