<?php

use App\Payments\Plan;
use App\Translation\Message;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Laravel\Cashier\Subscription;

$factory->define(Subscription::class, function (Faker $faker) {
    $randomPlan = Plan::AVAILABLE_PLANS[array_rand(Plan::AVAILABLE_PLANS)];
    return [
        'name' => 'default',
        'stripe_id' => str_random(8),
        'stripe_plan' => $randomPlan,
        'quantity' => 1,
        'user_id' => factory(User::class)->create()->id,
        'ends_at' => Carbon::now()->addMonth()
    ];
});
