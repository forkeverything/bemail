<?php

use App\Language;
use App\Translation\Message;
use App\Translation\TranslationStatus;
use App\User;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {

    $languageIDs = Language::all()->pluck('id')->toArray();

    return [
        'subject' => $faker->sentence(5, true),
        'body' => $faker->paragraph(3),
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'lang_src_id' => function() use ($faker, $languageIDs) {
            $numLanguages = count($languageIDs);
            $randomIndex = $faker->numberBetween(0, $numLanguages - 1);
            return array_splice($languageIDs, $randomIndex, 1)[0];
        },
        'lang_tgt_id' => function() use ($faker, $languageIDs) {
            return 2;
        },
        'translation_status_id' => function() {
            return TranslationStatus::all()->random()->id;
        }
    ];
});
