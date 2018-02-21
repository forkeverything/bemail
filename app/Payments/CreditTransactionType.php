<?php

namespace App\Payments;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereUpdatedAt($value)
 */
class CreditTransactionType extends Model
{
    /**\
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'add',              // bool - adding or subtracting credit?
        'name',
        'description'
    ];

    /**
     * For accepting an invitation to join.
     *
     * @return Model|null|static
     */
    public static function invite()
    {
        return CreditTransactionType::where('name', 'invite')->first();
    }

    /**
     * For inviting a friend to join.
     *
     * @return Model|null|static
     */
    public static function host()
    {
        return CreditTransactionType::where('name', 'host')->first();
    }

    /**
     * Paying for a Message.
     *
     * @return Model|null|static
     */
    public static function payment()
    {
        return CreditTransactionType::where('name', 'payment')->first();
    }

    /**
     * Manually added credit.
     *
     * @return Model|null|static
     */
    public static function manualAdd()
    {
        return CreditTransactionType::where('name', 'manual')->where('add', 1)->first();
    }

    /**
     * Manually added credit.
     *
     * @return Model|null|static
     */
    public static function manualDeduct()
    {
        return CreditTransactionType::where('name', 'manual')->where('add', 0)->first();
    }

}
