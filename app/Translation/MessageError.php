<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\MessageError
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $code
 * @property string $description
 * @property int $message_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\MessageError whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\MessageError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\MessageError whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\MessageError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\MessageError whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\MessageError whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MessageError extends Model
{
    /**
     * Mass-assignable fields
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'description',
        'message_id'
    ];
}
