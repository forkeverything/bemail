<?php


namespace App\Payments\Traits;

use App\Payments\Subscription;
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
     * Custom Subscription(s) relationship.
     *
     * @return mixed
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())
                    ->orderBy('created_at', 'desc');
    }


    /**
     * The first and only subscription.
     *
     * @return Subscription
     */
    public function subscription()
    {

        // Laravel Cashier allows for multiple subscriptions but
        // since we only have one subscription, we'll use this
        // method to automatically return the first one.

        return $this->subscriptions()->first();
    }

}