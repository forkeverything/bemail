<?php

namespace App\Payment;

use App\Payment\Credit\CreditTransaction;
use App\Translation\Message;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Payment\Receipt
 *
 * @property-read \App\Payment\Credit\CreditTransaction $creditTransaction
 * @property-read \App\Translation\Message $message
 * @mixin \Eloquent
 */
class Receipt extends Model
{

    /**
     * Mass-fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'plan',
        'amount_charged',
        'reversed',
        'message_id'
    ];

    /**
     * Always eager-load these relationships.
     *
     * @var array
     */
    protected $with = [
        'creditTransaction'
    ];

    /**
     * The Message that was paid.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * CreditTransaction used for payment.
     *
     * Could be null when User pays in full.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creditTransaction()
    {
        return $this->hasOne(CreditTransaction::class, 'receipt_id');
    }

    /**
     * New Receipt for given Message.
     *
     * @param Message $message
     * @return static
     */
    public static function newForMessage(Message $message)
    {
        return new static([
            'plan' => $message->owner->plan(),
            'message_id' => $message->id
        ]);
    }

    /**
     * The amount was charged to the User.
     *
     * @param null $amount
     * @return $this|mixed
     */
    public function amountCharged($amount = null)
    {
        if (is_null($amount)) {
            return $this->amount_charged;
        }
        $this->amount_charged = $amount;
        return $this;
    }

}
