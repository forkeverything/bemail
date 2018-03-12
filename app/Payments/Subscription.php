<?php


namespace App\Payments;


use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription as LaravelSubscription;

/**
 * Custom Laravel\Cashier\Subscription Wrapper
 *
 * @package App\Payments
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $name
 * @property string $stripe_id
 * @property string $stripe_plan
 * @property int $quantity
 * @property \Carbon\Carbon|null $trial_ends_at
 * @property \Carbon\Carbon|null $ends_at
 * @property int $user_id
 * @property-read \App\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereStripePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Subscription whereUserId($value)
 * @mixin \Eloquent
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
        return new Plan($this->user, $this->stripe_plan);
    }
}