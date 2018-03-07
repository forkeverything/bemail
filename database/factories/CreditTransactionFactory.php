<?php

use App\Payment\CreditTransaction;
use App\Payment\CreditTransactionType;
use App\Payment\MessageReceipt;
use Faker\Generator as Faker;

$factory->define(CreditTransaction::class, function (Faker $faker) {
    return [
        'amount' => $faker->numberBetween(0, 100),
        'credit_transaction_type_id' => CreditTransactionType::all()->random()->id,
        'message_receipt_id' => factory(MessageReceipt::class)->create()->id
    ];
});
