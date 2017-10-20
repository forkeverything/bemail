<?php

use App\Payments\CreditTransaction;
use App\Payments\CreditTransactionType;
use App\Payments\MessageReceipt;
use Faker\Generator as Faker;

$factory->define(CreditTransaction::class, function (Faker $faker) {
    return [
        'amount' => $faker->numberBetween(0, 100),
        'credit_transaction_type_id' => CreditTransactionType::all()->random()->id,
        'message_receipt_id' => factory(MessageReceipt::class)->create()->id
    ];
});
