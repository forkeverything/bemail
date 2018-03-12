<?php


namespace App\Payments;


use Laravel\Cashier\SubscriptionBuilder as LaravelSubscriptionBuilder;

class SubscriptionBuilder extends LaravelSubscriptionBuilder
{

    /**
     * The ID of the Plan being subscribed to.
     *
     * @var int
     */
    protected $planID;

    /**
     * Create custom subscription builder instance.
     *
     * @param $owner
     * @param $name
     * @param Plan $plan
     */
    public function __construct($owner, $name, Plan $plan)
    {
        parent::__construct($owner, $name, $plan->name());
        $this->planID = $plan->id;
}

    /**
     * OVERRIDE - Create a new Stripe subscription.
     *
     * @param  string|null  $token
     * @param  array  $options
     * @return \Laravel\Cashier\Subscription
     */
    public function create($token = null, array $options = [])
    {
        $customer = $this->getStripeCustomer($token, $options);

        $subscription = $customer->subscriptions->create($this->buildPayload());

        if ($this->skipTrial) {
            $trialEndsAt = null;
        } else {
            $trialEndsAt = $this->trialExpires;
        }

        return $this->owner->subscriptions()->create([
            'name' => $this->name,
            'stripe_id' => $subscription->id,
            'stripe_plan' => $this->plan,
            'quantity' => $this->quantity,
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => null,
            'plan_id' => $this->planID          // Added the Plan ID
        ]);
    }
}