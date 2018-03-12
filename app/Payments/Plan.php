<?php

namespace App\Payments;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Payments\Plan
 *
 * @property int $id
 * @property string $name
 * @property int $surcharge
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\Plan whereSurcharge($value)
 * @mixin \Eloquent
 */
class Plan extends Model
{
    /**
     * No created_at/updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cost',
        'surchage'
    ];

    /**
     * Get a Plan using it's name.
     *
     * @param $name
     * @return Model|static
     */
    public static function name($name)
    {
        return static::where('name', $name)->firstOrFail();
    }

    /**
     * Free Plan
     *
     * @return Model|static
     */
    public static function free()
    {
        return Plan::where('name', 'free')->firstOrFail();
    }

    /**
     * Regular Plan
     *
     * @return Model|static
     */
    public static function regular()
    {
        return Plan::where('name', 'regular')->firstOrFail();
    }

    /**
     * Professional Plan
     *
     * @return Model|static
     */
    public static function professional()
    {
        return Plan::where('name', 'professional')->firstOrFail();
    }

    /**
     * Name of the Plan.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Monthly recurring cost.
     *
     * @return mixed
     */
    public function cost()
    {
        return $this->cost;
    }

    /**
     * How much is added on top of unit price.
     *
     * @return int
     */
    public function surcharge()
    {
        return $this->surcharge;
    }

}
