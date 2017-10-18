<?php

namespace App\Payments;

use App\Message;
use App\User;
use Illuminate\Database\Eloquent\Model;


/**
 * Payment receipt for sending a Message.
 *
 * @mixin \Eloquent
 * @property-read \App\Message $message
 * @property-read \App\User $user
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $word_count
 * @property int $total_cost
 * @property int $message_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereWordCount($value)
 * @property-read \App\Payments\CreditTransaction $creditTransaction
 * @property int $cost_per_word
 * @property-read int|mixed $credits_used
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereCostPerWord($value)
 * @property int $reversed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\MessageReceipt whereReversed($value)
 */
class MessageReceipt extends Model
{
    /**
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'word_count',
        'cost_per_word',
        'amount_charged',
        'message_id',
        'user_id'
    ];

    /**
     * Appended dynamic attributes.
     *
     * @var array
     */
    protected $appends = [
        'credits_used'
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
     * The User that paid for the Message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * CreditTransaction associated with this payment.
     * Could potentially be null, User without credits paid in
     * full.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creditTransaction()
    {
        return $this->hasOne(CreditTransaction::class, 'message_receipt_id');
    }

    /**
     * How much word credits was used.
     *
     * @return int
     */
    public function getCreditsUsedAttribute()
    {
        $creditTransaction = $this->creditTransaction;
        return  $creditTransaction ? $creditTransaction->amount : 0;
    }



}
