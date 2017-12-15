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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Gengo Callback Route
Route::post('gengo', 'GengoController@postHandleCallback');

// Postmark Incoming Mail Web-hook
Route::post('postmark', 'PostmarkController@postIncoming');

