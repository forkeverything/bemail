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
     * Name of the user subscription.
     *
     * @return string
     */
    public static function main()
    {
        return 'main';
    }

    /**
     * Get the plan that this subscription is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * OVERRIDE - Swap the subscription to a new Stripe plan.
     *
     * @param  string  $plan
     * @return $this
     */
    public function swap($plan)
    {
        $subscription = $this->asStripeSubscription();

        $subscription->plan = $plan;

        $subscription->prorate = $this->prorate;

        if (! is_null($this->billingCycleAnchor)) {
            $subscription->billingCycleAnchor = $this->billingCycleAnchor;
        }

        // If no specific trial end date has been set, the default behavior should be
        // to maintain the current trial state, whether that is "active" or to run
        // the swap out with the exact number of days left on this current plan.
        if ($this->onTrial()) {
            $subscription->trial_end = $this->trial_ends_at->getTimestamp();
        } else {
            $subscription->trial_end = 'now';
        }

        // Again, if no explicit quantity was set, the default behaviors should be to
        // maintain the current quantity onto the new plan. This is a sensible one
        // that should be the expected behavior for most developers with Stripe.
        if ($this->quantity) {
            $subscription->quantity = $this->quantity;
        }

        $subscription->save();

        $this->user->invoice();

        $this->fill([
            'stripe_plan' => $plan,
            'ends_at' => null,
            'plan_id' => Plan::name($plan)->id                  // Updated with new plan id
        ])->save();

        return $this;
    }
}