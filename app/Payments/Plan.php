<?php

namespace App\Payments;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * App\Payments\Plan
 *
 * Plan class is NOT an Eloquent Model. By using a custom class,
 * only have to override Subscription class instead of all
 * classes where the 'plan_id' needed to be changed.
 * 
 * @package App\Payments
 */
class Plan
{

    /**
     * Free Plan
     */
    const FREE = 'free';
    /**
     * Regular Plan
     */
    const REGULAR = 'regular';
    /**
     * Professional Plan
     */
    const PROFESSIONAL = 'professional';
    /**
     * Available Plans
     */
    const AVAILABLE_PLANS = [
        self::FREE,
        self::REGULAR,
        self::PROFESSIONAL
    ];

    /**
     * Plan name.
     *
     * @var string
     */
    private $name;

    /**
     * Create new Plan instance.
     *
     * @param $name
     */
    public function __construct($name)
    {
        if (!$this->isValidPlanName($name)) {
            throw new InvalidArgumentException('Invalid plan provided.');
        }

        $this->name = $name;
    }

    /**
     * Only plan names in available plans are valid.
     *
     * @param $name
     * @return bool
     */
    private function isValidPlanName($name)
    {
        return in_array($name, self::AVAILABLE_PLANS);
    }

    /**
     * Instantiate a 'free' plan instance.
     *
     * @return static
     */
    public static function free()
    {
        return new static(Plan::FREE);
    }

    /**
     * Instantiate a 'regular' plan instance.
     *
     * @return static
     */
    public static function regular()
    {
        return new static(Plan::REGULAR);
    }

    /**
     * Instantiate a 'professional' plan instance.
     *
     * @return static
     */
    public static function professional()
    {
        return new static(Plan::PROFESSIONAL);
    }

    /**
     * Gets the Plan for given user.
     *
     * @param User $user
     * @return Plan
     */
    public static function forUser(User $user)
    {
        if (!$user->subscribed()) {
            $name = self::FREE;
        } else {
            $name = $user->subscription()->stripe_plan;
        }
        return new Plan($name);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get cost.
     *
     * @return int
     */
    public function cost()
    {
        switch ($this->name) {
            case 'free':
                return 0;
                break;
            case 'regular':
                return 1500;
                break;
            case 'professional':
                return 4000;
                break;
            default:
                break;
        }
    }

    /**
     * Get surcharge.
     *
     * @return int
     */
    public function surcharge()
    {
        switch ($this->name) {
            case 'free':
                return 7;
                break;
            case 'regular':
                return 2;
                break;
            case 'professional':
                return 0;
                break;
            default:
                break;
        }
    }

    /**
     * All User(s) for given plan.
     *
     * This is an expensive query. Should only be used by system and
     * sparingly at that.
     *
     * @return Collection
     */
    public function users()
    {
        return User::all()->filter(function ($user) {
            /**
             * @var User $user
             */
            return $user->plan()->name() == self::name();
        });
    }

}
