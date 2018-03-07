<?php

namespace App\Payment;

use App\Translation\Message;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Payments\CreditTransaction
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $add
 * @property int|null $message_receipt_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransaction whereAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransaction whereMessageReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransaction whereUpdatedAt($value)
 * @property-read \App\Payment\MessageReceipt|null $messageReceipt
 * @property-read \App\Payment\CreditTransactionType $type
 * @property int $amount
 * @property int $credit_transaction_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransaction whereCreditTransactionTypeId($value)
 * @property-read \App\User $user
 */
class CreditTransaction extends Model
{

    /**
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'credit_transaction_type_id',
        'user_id',
        'message_receipt_id'
    ];

    /**
     * Type of transaction.
     *
     * What was the transaction for? These are pre-defined and seeded
     * to minimize potential errors and make changes easier in
     * the future.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(CreditTransactionType::class, 'credit_transaction_type_id', 'id');
    }

    /**
     * User who's credit was adjusted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * MessageReceipt this transaction was for.
     *
     * Could be null, when the transaction wasn't for the
     * payment of a message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function messageReceipt()
    {
        return $this->belongsTo(MessageReceipt::class, 'message_receipt_id');
    }
}
