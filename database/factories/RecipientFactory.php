<?php

use App\Translation\Recipient;
use Faker\Generator as Faker;

$factory->define(Recipient::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'message_id' => factory(\App\Translation\Message::class)->create()->id
    ];
});
