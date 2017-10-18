<?php


namespace App\Payments\Accountants;


use App\Message;
use App\Payments\Contracts\Accountant;
use App\User;

class LaravelCashierAccountant implements Accountant
{

    public function process(Message $message)
    {
        // TODO: Implement process() method.
    }

    public function calculateFee(Message $message)
    {
        // TODO: Implement calculateFee() method.
    }

    public function deductWordCredit($amount, User $user)
    {
        // TODO: Implement deductWordCredit() method.
    }

    public function charge(User $user)
    {
        // TODO: Implement charge() method.
    }
}