<?php

use App\Payment\MessageReceipt;
use App\Translation\Message;
use Faker\Generator as Faker;

$factory->define(MessageReceipt::class, function (Faker $faker) {
    return [
        'cost_per_word' => $faker->numberBetween(1, 100),
        'amount_charged' => $faker->numberBetween(3, 10000),
        'reversed' => 0,
        'message_id' => factory(Message::class)->create()->id
    ];
});
