<?php

namespace App\Payments\Contracts;


use App\Language;
use App\Translation\Message;
use App\Payments\CreditTransactionType;
use App\User;

interface Accountant
{

    /**
     * Process the payment for given Message.
     *
     * @param Message $message
     * @return mixed
     */
    function process(Message $message);

    /**
     * Change the amount of word credits a User has.
     * Should only be done here so changes are recorded.
     *
     * @param User $user
     * @param CreditTransactionType $creditTransactionType
     * @param $amount
     * @return bool
     */
    function adjustCredits(User $user, CreditTransactionType $creditTransactionType, $amount);

    // TODO ::: Implement User subscription method

}