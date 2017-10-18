<?php

namespace App\Payments\Contracts;


use App\Message;
use App\User;

interface Accountant
{
    public function process(Message $message);
    public function calculateFee(Message $message);
    public function deductWordCredit($amount, User $user);
    public function charge(User $user);
}