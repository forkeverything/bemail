<?php

namespace App\Payment\Credit\Transaction;

use App\Payment\Credit\CreditTransaction;
use Illuminate\Database\Eloquent\Model;

class CreditTransactionType extends Model
{

    /**
     * No created_at/updated_at columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**\
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * For accepting an invitation to join.
     *
     * @return Model|static
     */
    public static function invite()
    {
        return CreditTransactionType::where('name', 'invite')->firstOrFail();
    }

    /**
     * For inviting a friend to join.
     *
     * @return Model|static
     */
    public static function host()
    {
        return CreditTransactionType::where('name', 'host')->firstOrFail();
    }

    /**
     * Paying for a Message.
     *
     * @return Model|static
     */
    public static function payment()
    {
        return CreditTransactionType::where('name', 'payment')->firstOrFail();
    }

    /**
     * Manually updated credits.
     *
     * @return Model|static
     */
    public static function manual()
    {
        return CreditTransactionType::where('name', 'manual')->firstOrFail();
    }

    /**
     * CreditTransaction(s) that are of this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(CreditTransaction::class, 'credit_transaction_type_id');
    }

}
