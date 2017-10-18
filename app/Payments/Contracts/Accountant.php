<?php

namespace App\Payments\Contracts;


use App\Language;
use App\Message;
use App\Payments\CreditTransactionType;
use App\User;

interface Accountant
{
    /**
     * Accountant constructor.
     * Need to know the User who we will be calculating
     * payment for.
     *
     * @param User $user
     */
    function __construct(User $user);

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
     * @param CreditTransactionType $creditTransactionType
     * @param $amount
     * @return mixed
     */
    function adjustCredits(CreditTransactionType $creditTransactionType, $amount);

    /**
     * Charge the User given amount.
     *
     * @param $amount
     * @return mixed
     */
    function charge($amount);


}