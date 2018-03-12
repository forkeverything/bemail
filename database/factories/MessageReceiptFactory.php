<?php

use App\Payments\MessageReceipt;
use App\Payments\Plan;
use App\Translation\Message;
use Faker\Generator as Faker;

$factory->define(MessageReceipt::class, function (Faker $faker) {

    /**
     * Get a random plan.
     *
     * @var Plan $plan
     */
    $plan = Plan::AVAILABLE_PLANS[array_rand(Plan::AVAILABLE_PLANS)];

    return [
        'plan' => $plan,
        'cost_per_word' => $faker->numberBetween(1, 100),
        'amount_charged' => $faker->numberBetween(3, 10000),
        'reversed' => 0,
        'message_id' => factory(Message::class)->create()->id
    ];
});
