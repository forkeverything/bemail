<?php


namespace App\Payments\Traits;

use App\Payments\Plan;
use App\Payments\Subscription;
use App\Payments\SubscriptionBuilder;
use Laravel\Cashier\Billable as LaravelBillable;

/**
 * Custom Laravel Billable Trait Wrapper.
 *
 * @package App\Payments\Traits
 */
trait Billable
{

    use LaravelBillable;

    /**
     * Begin creating a new subscription using custom SubscriptionBuilder.
     *
     * @param  string  $subscription
     * @param  Plan $plan
     * @return \Laravel\Cashier\SubscriptionBuilder
     */
    public function newSubscription($subscription, Plan $plan)
    {
        return new SubscriptionBuilder($this, $subscription, $plan);
    }

    /**
     * Use our custom Subscription(s) model.
     *
     * @return mixed
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())->orderBy('created_at', 'desc');
    }

    /**
     * The one and only subscription that a User has.
     *
     * @return mixed
     */
    public function subscription()
    {

        // Laravel Cashier allows for multiple subscriptions but
        // since we only have one subscription, we'll use this
        // method to automatically return the first one.

        return $this->subscriptions()->first();
    }

}