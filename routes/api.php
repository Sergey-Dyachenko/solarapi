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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/comments/{id}', 'CommentController@show');
// Route::get('/comments', 'CommentController@index');
// Route::post('/comments', 'CommentController@create');
// Route::put('/comments/{id}', 'CommentController@update');
// Route::put('/comments/reply/{id}', 'CommentController@reply');
// Route::delete('/comments/{id}', 'CommentController@delete');

Route::resource('comments', 'CommentController');
