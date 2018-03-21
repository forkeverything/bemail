<?php

use App\Payment\Plan;
use App\Payment\Receipt;
use App\Translation\Message;
use Faker\Generator as Faker;

$factory->define(Receipt::class, function (Faker $faker) {

    /**
     * Get a random plan.
     *
     * @var Plan $plan
     */
    $plan = Plan::AVAILABLE_PLANS[array_rand(Plan::AVAILABLE_PLANS)];

    return [
        'plan' => $plan,
        'amount_charged' => $faker->numberBetween(100, 1500),
        'reversed' => 0,
        'message_id' => factory(Message::class)->create()->id
    ];
});
