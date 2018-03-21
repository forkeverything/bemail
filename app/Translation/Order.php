<?php

namespace App\Translation;

use App\Translation\Order\OrderStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\Order
 *
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $id
 * @property int $unit_count
 * @property int $unit_price
 * @property int $message_id
 * @property int $order_status_id
 * @property-read \App\Translation\Message $message
 * @property-read \App\Translation\Order\OrderStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereUnitCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order whereUpdatedAt($value)
 * @mixin \Eloquent
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
        'unit_count',
        'unit_price',
        'message_id',
        'order_status_id'
    ];

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
     * New Order for given Message'.
     *
     * @param Message $message
     * @return $this|Model
     */
    public static function newForMessage(Message $message)
    {
        return new static([
            'message_id' => $message->id,
            'order_status_id' => OrderStatus::available()->id
        ]);
    }

    /**
     * Order ID.
     *
     * @param null $id
     * @return $this|int
     */
    public function id($id = null)
    {
        if (is_null($id)) {
            return $this->id;
        }
        $this->id = $id;
        return $this;
    }

    /**
     * Number of units to translate.
     *
     * @param null $unitCount
     * @return $this|int
     */
    public function unitCount($unitCount = null )
    {
        if (is_null($unitCount)) {
            return $this->unit_count;
        }
        $this->unit_count = $unitCount;
        return $this;
    }

    /**
     * Price in cents per unit.
     *
     * @param null $unitPrice
     * @return $this|int
     */
    public function unitPrice($unitPrice = null )
    {
        if (is_null($unitPrice)) {
            return $this->unit_price;
        }
        $this->unit_price = $unitPrice;
        return $this;
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
