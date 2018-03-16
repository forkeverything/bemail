<?php

use App\Language;
use App\Translation\Message;
use App\User;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {

    $languageIDs = Language::all()->pluck('id')->toArray();
    $sourceLanguageId = array_splice($languageIDs, array_rand($languageIDs), 1)[0];
    $targetLanguageId = array_splice($languageIDs, array_rand($languageIDs), 1)[0];

    return [
        'subject' => $faker->sentence(5, true),
        'body' => $faker->paragraph(3),
        'translated_body' => function() use ($faker){
            return $faker->boolean(40) ? $faker->paragraph(2) : '';
        },
        'auto_translate_reply' => $faker->boolean(80),
        'send_to_self' => $faker->boolean(30),
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'lang_src_id' => $sourceLanguageId,
        'lang_tgt_id' => $targetLanguageId
    ];
});
