<?php

namespace App\Payments;

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
 * @property-read \App\Payments\CreditTransaction $creditTransaction
 * @property-read \App\Translation\Message $message
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereCostPerWord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereReversed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereWordCount($value)
 * @mixin \Eloquent
 * @property int $amount_charged
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereAmountCharged($value)
 * @property string $plan
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt wherePlan($value)
 */
class MessageReceipt extends Model
{
    /**
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'plan',
        'cost_per_word',
        'amount_charged',
        'reversed',
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

    /**
     * Make a new MessageReceipt.
     *
     * @param Message $message
     * @param $unitPrice
     * @param $amount
     * @return $this|Model
     */
    public static function makeFor(Message $message, $unitPrice, $amount)
    {
        return static::create([
            'plan' => $message->owner->plan()->name(),
            'cost_per_word' => $unitPrice,
            'amount_charged' => $amount,
            'message_id' => $message->id
        ]);
    }

}
