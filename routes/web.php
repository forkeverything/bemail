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

Route::get('/test', function (\App\Translation\Contracts\Translator $translator) {

//    dd(gmdate('U'));
//    dd(hash_hmac('sha1', "1508572868429", env('GENGO_SECRET')));

    $service = new \Gengo\Service;

    $arrayOfLanguagePairs = json_decode($service->getLanguagePairs('zh'))->response;


    return array_filter($arrayOfLanguagePairs, function ($languagePair) {
        return true;
    });

});

Route::get('/mail/test', function () {
    $message = factory(\App\Translation\Message::class)->create();
    factory(\App\Translation\MessageError::class)->create(['message_id' => $message->id]);
    return new \App\Translation\Mail\SystemTranslationError($message);
//    $recipients = factory(\App\Translation\Recipient::class, 5)->create()->pluck('id')->toArray();
//    $message->recipients()->sync($recipients);
    $message = \App\Translation\Message::with(['recipients', 'sourceLanguage', 'targetLanguage', 'receipt.creditTransaction'])->first();
    return new \App\Translation\Mail\ReceivedRequest($message);
    $message->fresh()->with(['recipients', 'sourceLanguage', 'targetLanguage']);
    return $message;
    Mail::to('mike@bemail.io')->send(new \App\Mail\WelcomeMail(\App\User::first()));
    return 'sent!';
});

