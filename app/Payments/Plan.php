<?php

namespace App\Payments;

use InvalidArgumentException;

/**
 * App\Payments\Plan
 *
 * Plan class is NOT an Eloquent Model. By using a custom class,
 * only have to override Subscription class instead of all
 * classes where the 'plan_id' needed to be changed.
 *
 * @property int $id
 * @property string $name
 * @property int $surcharge
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Plan whereSurcharge($value)
 * @mixin \Eloquent
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
     * Available plan names.
     *
     * This is the same as the plan id for Stripe.
     */
    const PLANS = [
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
     * Monthly cost for plan (US cents).
     *
     * @var int
     */
    private $cost;

    /**
     * Service cost surcharge added to translator unit price (US cents).
     *
     * @var int
     */
    private $surcharge;

    /**
     * Create new Plan instance.
     *
     * @param $name
     */
    public function __construct($name)
    {
        if (!in_array($name, self::PLANS)) {
            throw new InvalidArgumentException('Invalid plan provided.');
        }

        $this->name = $name;

        $this->setCost()
             ->setSurcharge();
    }

    /**
     * Set cost property value.
     *
     * @return $this
     */
    private function setCost()
    {
        switch ($this->name) {
            case 'free':
                $this->cost = 0;
                break;
            case 'regular':
                $this->cost = 1500;
                break;
            case 'professional':
                $this->cost = 4000;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Set surchage property value.
     *
     * @return $this
     */
    private function setSurcharge()
    {
        switch ($this->name) {
            case 'free':
                $this->surcharge = 7;
                break;
            case 'regular':
                $this->surcharge = 2;
                break;
            case 'professional':
                $this->surcharge = 0;
                break;
            default:
                break;
        }

        return $this;
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
        return $this->cost;
    }

    /**
     * Get surcharge.
     *
     * @return int
     */
    public function surcharge()
    {
        return $this->surcharge;
    }

}