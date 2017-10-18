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
 * @property string $slug
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransactionType whereUpdatedAt($value)
 */
class CreditTransactionType extends Model
{
    /**
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'add',
        'slug',
        'description'
    ];
}
