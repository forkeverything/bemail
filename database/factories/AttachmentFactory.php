<?php

use App\Translation\Message;
use Faker\Generator as Faker;

$factory->define(\App\Translation\Attachment::class, function (Faker $faker) {
    return [
        'file_name' => str_random(8),
        'original_file_name' => $faker->word . '.txt',
        'path' => function (array $attachment) {
            return 'testing/file/attachment/' . $attachment["original_file_name"];
        },
        'size' => $faker->numberBetween(0, 20000),
        'message_id' => factory(Message::class)->create()->id
    ];
});
