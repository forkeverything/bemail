<?php

use App\Translation\Recipient;
use Faker\Generator as Faker;

$factory->define(Recipient::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'user_id' => factory(\App\User::class)->create()->id
    ];
});
