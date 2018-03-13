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
    return view('landing');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Compose
Route::get('compose', 'MessagesController@getCompose')->name('compose');
Route::post('compose', 'MessagesController@postSendMessage')->name('sendMessage');

// Account
Route::get('/account', 'AccountController@getSettings');
Route::post('/account', 'AccountController@postUpdateSettings');

// Languages
Route::get('/languages/price/src/{langSrc}/tgt/{langTgt}', 'LanguagesController@getUnitPrice');

// Subscriptions
Route::get('/subscription', 'SubscriptionController@getSubscriptionsPage');
Route::post('/subscription/plan/{plan}', 'SubscriptionController@postNewSubscription');
Route::put('/subscription/plan/{plan}', 'SubscriptionController@putChangePlan');
Route::put('/subscription/credit_card', 'SubscriptionController@putUpdateCard');
Route::delete('/subscription/plan', 'SubscriptionController@deleteCancelPlan');
Route::put('/subscription/plan', 'SubscriptionController@putResumePlan');




