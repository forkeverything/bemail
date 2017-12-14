<?php

use App\Translation\Message;
use App\Translation\Recipient;
use App\Translation\RecipientType;
use Faker\Generator as Faker;

$factory->define(Recipient::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'recipient_type_id' => RecipientType::standard()->id,
        'message_id' => factory(Message::class)->create()->id
    ];
});
