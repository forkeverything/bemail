<?php

namespace App\Payment;

use App\Translation\Message;
use App\User;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Payments\MessageReceipt
 * 
 * Transaction receipt for a Message that contains the breakdown of
 * the cost of sending the Message.
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $word_count
 * @property int $cost_per_word
 * @property int $reversed
 * @property int $message_id
 * @property int $user_id
 * @property-read \App\Payment\CreditTransaction $creditTransaction
 * @property-read \App\Translation\Message $message
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereCostPerWord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereReversed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereWordCount($value)
 * @mixin \Eloquent
 * @property int $amount_charged
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\MessageReceipt whereAmountCharged($value)
 */
class MessageReceipt extends Model
{
    /**
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'cost_per_word',
        'amount_charged',
        'message_id'
    ];

    /**
     * The Message this payment receipt is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * CreditTransaction used for this payment.
     * Could be null when User pays in full.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creditTransaction()
    {
        return $this->hasOne(CreditTransaction::class, 'message_receipt_id');
    }

}
