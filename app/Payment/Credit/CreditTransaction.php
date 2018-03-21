<?php

namespace App\Payment\Credit;

use App\Payment\Credit\Transaction\CreditTransactionType;
use App\Payment\Receipt;
use App\User;
use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(CreditTransactionType::class, 'credit_transaction_type_id', 'id');
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
     * Records credit transaction.
     *
     * @param User $user
     * @param CreditTransactionType $type
     * @param $amount
     * @return $this|Model
     */
    public static function record(User $user, CreditTransactionType $type, $amount)
    {
        return static::create(([
            'user_id' => $user->id,
            'credit_transaction_type_id' => $type->id,
            'amount' => $amount
        ]));
    }
}
