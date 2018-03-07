<?php

namespace App\Payment;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Payments\CreditTransactionType
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $add
 * @property string $name
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransactionType whereAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransactionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment\CreditTransactionType whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment\CreditTransaction[] $transactions
 */
class CreditTransactionType extends Model
{
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
