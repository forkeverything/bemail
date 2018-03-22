<?php

use App\Error;
use App\Translation\Message;
use Faker\Generator as Faker;

$factory->define(Error::class, function (Faker $faker) {
    $message = factory(Message::class)->create();
    // TODO ::: dynamically set classes that could have errors.
    return [
        'code' => $faker->numberBetween(100, 1000),
        'msg' => $faker->sentence,
        'errorable_id' => $message->id,
        'errorable_type' => get_class($message)
    ];
});
