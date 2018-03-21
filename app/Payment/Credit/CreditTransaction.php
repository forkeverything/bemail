<?php

namespace App\Payment\Credit;

use App\Payment\Credit\Transaction\CreditTransactionType;
use App\Payment\Receipt;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Payment\Credit\CreditTransaction
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $amount
 * @property int $credit_transaction_type_id
 * @property int $user_id
 * @property int|null $receipt_id
 * @property-read \App\Payment\Receipt|null $receipt
 * @property-read \App\Payment\Credit\Transaction\CreditTransactionType $type
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\Credit\CreditTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\Credit\CreditTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\Credit\CreditTransaction whereCreditTransactionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\Credit\CreditTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\Credit\CreditTransaction whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\Credit\CreditTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\Credit\CreditTransaction whereUserId($value)
 * @mixin \Eloquent
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
        'receipt_id',
        'user_id'
    ];

    /**
     * RecipientType of transaction.
     *
     * What was the transaction for? These are pre-defined and seeded
     * to minimize potential errors and make changes easier in
     * the future.
     *
     * @param CreditTransactionType|null $creditTransactionType
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|$this
     */
    public function type(CreditTransactionType $creditTransactionType = null)
    {
        if (is_null($creditTransactionType)) {
            return $this->belongsTo(CreditTransactionType::class, 'credit_transaction_type_id', 'id');
        }
        $this->credit_transaction_type_id = $creditTransactionType->id;
        return $this;
    }

    /**
     * The User that had credit adjusted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Receipt, if there is one, that this transaction is on.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    /**
     * New CreditTransaction for a given User.
     *
     * @param User $user
     * @return static
     */
    public static function newForUser(User $user)
    {
        return new static([
            'user_id' => $user->id
        ]);
    }

    /**
     * The amount of credits adjusted.
     *
     * @param null $amount
     * @return $this|int
     */
    public function amount($amount = null)
    {
        if (is_null($amount)) {
            return $this->amount;
        }

        $this->amount = $amount;
        return $this;
    }

}
