<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\OrderStatus
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\OrderStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\OrderStatus whereId($value)
 */
class OrderStatus extends Model
{

    /**
     * No created_at / updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Available.
     *
     * Waiting for translator to take the order.
     *
     * @return OrderStatus
     */
    public static function available()
    {
        return static::where('description', 'available')->first();
    }

    /**
     * Pending.
     *
     * Work has begun on translating the Message.
     *
     * @return mixed
     */
    public static function pending()
    {
        return static::where('description', 'pending')->first();
    }

    /**
     * Complete.
     *
     * Translation is complete and approved.
     *
     * @return mixed
     */
    public static function complete()
    {
        return static::where('description', 'complete')->first();
    }

    /**
     * Cancelled.
     *
     * Translation is cancelled before being completed.
     *
     * @return mixed
     */
    public static function cancelled()
    {
        return static::where('description', 'cancelled')->first();
    }

    /**
     * Error
     *
     * Something very bad happened and the order is not: in
     * the queue, being currently translated, ever going
     * to be completed, and able to be cancelled.
     *
     * @return mixed
     */
    public static function error()
    {
        return static::where('description', 'error')->first();
    }

}
