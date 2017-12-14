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
 * @property int $message_id
 * @property-read \App\Translation\Message $message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereMessageId($value)
 */
class Recipient extends Model
{
    protected $fillable = [
        'email',
        'recipient_type_id',
        'message_id'
    ];

    /**
     * Type of Recipient: standard, cc, bcc
     * This corresponds to the respective fields in an email. Need to
     * specify type so that emails can be sent accordingly for
     * reply Message(s).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(RecipientType::class, 'recipient_type_id');
    }

    /**
     * Message that this Recipient received.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * Sender that sent the Message that this Recipient received.
     * If the Message was an original message (via app) then this will be the User model.
     * Otherwise, if it was a reply, the sender will be the sender's email (string).
     *
     * @return User|string
     */
    public function getSender()
    {
        return $this->message->reply_sender_email ?: $this->message->user;
    }
}
