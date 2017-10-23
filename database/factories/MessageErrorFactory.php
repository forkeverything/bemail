<?php

use App\Translation\MessageError;
use Faker\Generator as Faker;

$factory->define(MessageError::class, function (Faker $faker) {
    return [
        'code' => $faker->numberBetween(0, 4000),
        'description' => $faker->sentence(5),
        'message_id' => factory(\App\Translation\Message::class)->create()->id
    ];
});
