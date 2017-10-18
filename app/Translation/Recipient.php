<?php

namespace App\Translation;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\Recipient
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $email
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Message[] $messages
 * @property-read \App\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereUserId($value)
 * @mixin \Eloquent
 */
class Recipient extends Model
{
    protected $fillable = [
        'email',
        'user_id'
    ];

    /**
     * Message(s) sent to this Recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function messages()
    {
        return $this->belongsToMany(Message::class, 'message_recipient', 'recipient_id', 'message_id');
    }

    /**
     * User that sends messages to this recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Search for Recipient(s) that belong to given User.
     *
     * @param User $user
     * @return mixed
     */
    public static function belongingTo(User $user)
    {
        return static::where('user_id', $user->id);
    }
}
