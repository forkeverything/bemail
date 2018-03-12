<?php

namespace App\Http\Controllers;

use App\Payments\Events\CustomerSubscriptionDeleted;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeController extends CashierController
{

    // Implement custom webhook events here. They have to follow a certain naming
    // convention according to event occurred. Refer the following for info:
    // https://laravel.com/docs/5.5/billing#handling-stripe-webhooks

    protected function handleCustomerSubscriptionDeleted(array $payload)
    {

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            /** @var User $user */
            event(new CustomerSubscriptionDeleted($user));
        }

        return new Response('Webhook Handled', 200);
    }
}
