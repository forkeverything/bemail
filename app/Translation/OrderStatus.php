<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\OrderStatus
 *
 * @mixin \Eloquent
 */
class OrderStatus extends Model
{

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
