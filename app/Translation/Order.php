<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\Order
 *
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $id
 * @property int $message_id
 * @property int $order_status_id
 * @property-read \App\Translation\Message $message
 * @property-read \App\Translation\OrderStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereUpdatedAt($value)
 */
class Order extends Model
{

    /**
     * Non-incrementing ID.
     *
     * Allows order id to be explicitly set after receiving
     * from another service - ie. Gengo.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Mass-fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'message_id',
        'order_status_id'
    ];

    /**
     * Creates an Order for given Message and 'id'.
     *
     * @param Message $message
     * @param $id
     * @return $this|Model
     */
    public static function createForMessage(Message $message, $id)
    {
        return static::create([
            'id' => $id,
            'message_id' => $message->id,
            'order_status_id' => OrderStatus::available()->id
        ]);
    }

    /**
     * The Message to be translated.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Current status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    /**
     * Update current status.
     *
     * @param OrderStatus $status
     * @return bool
     */
    public function updateStatus(OrderStatus $status)
    {
        return $this->update([
            'order_status_id' => $status->id
        ]);
    }

}
