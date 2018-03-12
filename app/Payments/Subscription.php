<?php


namespace App\Payments;


use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription as LaravelSubscription;

/**
 * Custom Laravel\Cashier\Subscription Wrapper
 *
 * @package App\Payments
 */
class Subscription extends LaravelSubscription
{

    /**
     * The name of the only subscription.
     */
    const MAIN = 'main';

    /**
     * Get the plan that this subscription is for.
     *
     * @return Plan
     */
    public function plan()
    {
        return new Plan($this->stripe_plan);
    }
}