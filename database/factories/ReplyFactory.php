<?php

use App\Translation\Message;
use App\Translation\Reply;
use Faker\Generator as Faker;

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'sender_email' => $faker->email,
        'sender_name' => $faker->name,
        'original_message_id' => factory(Message::class)->create()->id,
    ];
});
