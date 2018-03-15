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

// Routes here are under the /api/*.

// Gengo Callback Route
Route::post('gengo', 'GengoController@postCallback');

// Postmark Incoming Mail Web-hook
Route::post('postmark', 'PostmarkController@postInboundMail');

// Stripe Web-Hook Controller
Route::post('stripe',  'StripeController@handleWebhook');

