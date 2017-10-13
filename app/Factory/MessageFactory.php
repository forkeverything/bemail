<?php


namespace App\Factory;

use App\Http\Requests\CreateMessageRequest;
use App\User;

class MessageFactory
{
    static function make(CreateMessageRequest $createMessageRequest, User $user)
    {
        // create model
            // assign languages
        // create recipients
        // attach recipients
        // (?) TODO ::: fire off events
    }
}