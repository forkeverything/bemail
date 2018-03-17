<?php

use App\Translation\Message;
use App\Translation\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'id' => $faker->numberBetween(90000, 100000),
        'unit_count' => $faker->numberBetween(50, 500),
        'unit_price' => $faker->numberBetween(2, 15),
        'message_id' => factory(Message::class)->create()->id,
        'order_status_id' => \App\Translation\OrderStatus::all()->random()->id
    ];
});
