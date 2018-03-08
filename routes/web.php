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

Route::get('/test', function (\App\Translation\Contracts\Translator $translator) {

//    dd(gmdate('U'));
//    dd(hash_hmac('sha1', "1508572868429", env('GENGO_SECRET')));

    $translator = new \App\Translation\Translators\GengoTranslator();
    return $translator->languagePair('zh', 'en');


});

Route::get('/test/mail', function () {

    $originalMessage = factory(\App\Translation\Message::class)->create([
        'body' => 'original message',
        'translated_body' => 'This is the translated message. Proin tincidunt eget ante viverra cursus. Praesent tincidunt nisi ac porta mollis. Praesent eu nibh et lorem convallis sollicitudin. Etiam venenatis sem nec ante ornare, eget suscipit felis venenatis. Vestibulum justo neque, commodo feugiat aliquet in, tincidunt placerat velit. Maecenas a purus risus. Vestibulum tempus non nisi vel luctus.'
    ]);

    $mail = new \App\Translation\Mail\TranslatedMessageForSendToSelf($originalMessage);

    Mail::to('mail@wumike.com')->send($mail);

    return 'done!';

    // Message we're translating
    $reply1 = factory(\App\Translation\Reply::class)->create([
        'original_message_id' => $originalMessage->id
    ]);
    $message1 = factory(\App\Translation\Message::class)->create([
        'auto_translate_reply' => 1,
        'translated_body' => 'This is the translated message. Proin tincidunt eget ante viverra cursus. Praesent tincidunt nisi ac porta mollis. Praesent eu nibh et lorem convallis sollicitudin. Etiam venenatis sem nec ante ornare, eget suscipit felis venenatis. Vestibulum justo neque, commodo feugiat aliquet in, tincidunt placerat velit. Maecenas a purus risus. Vestibulum tempus non nisi vel luctus.',
        'reply_id' => $reply1->id
    ]);

    // Make another reply that belongs to the same mesage
    $reply2 = factory(\App\Translation\Reply::class)->create([
        'original_message_id' => $reply1->original_message_id
    ]);
    $message2 = factory(\App\Translation\Message::class)->create([
        'auto_translate_reply' => 1,
        'body' => 'sibling reply message',
        'reply_id' =>  $reply2->id
    ]);

    $mail = new \App\Translation\Mail\TranslatedMessageForRecipient($message1);

//    return $mail;

    Mail::to('mail@wumike.com')->send($mail);
    return 'sent';
});