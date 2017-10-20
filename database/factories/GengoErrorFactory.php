<?php

use Faker\Generator as Faker;

$factory->define(\App\GengoError::class, function (Faker $faker) {
    return [
        'code' => $faker->numberBetween(0, 4000),
        'description' => $faker->sentence(5),
        'message_id' => factory(\App\Translation\Message::class)->create()->id
    ];
});
