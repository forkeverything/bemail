<?php

namespace App\Translation;

use App\Translation\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Reply class
 * 
 * This class encapsulates a Message when it is a reply to another Message.
 * This is done because some senders might not have accounts and we need
 * to store sender info consistently.
 *
 * @package App\Translation
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $sender_email
 * @property string|null $sender_name
 * @property int $original_message_id
 * @property-read \App\Translation\Message $attachedMessage
 * @property-read \App\Translation\Message $originalMessage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Reply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Reply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Reply whereOriginalMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Reply whereSenderEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Reply whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Reply whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Reply extends Model
{

    /**
     * Mass-assignable fields.
     *
     * @var array
     */
    protected $fillable = [
        'sender_email',
        'sender_name',
        'original_message_id'
    ];

    /**
     * Reply's Message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attachedMessage()
    {
        return $this->hasOne(Message::class, 'reply_id');
    }

    /**
     * Original Message this is replying to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function originalMessage()
    {
        return $this->belongsTo(Message::class, 'original_message_id');
    }

    /**
     * Create a Message for this Reply.
     *
     * @param $recipients
     * @param $subject
     * @param $body
     * @param $attachments
     * @return MessageFactory
     */
    public function createMessage($recipients, $subject, $body, $attachments = [])
    {
        $factory = new MessageFactory();
        $factory->setReply($this)
                ->recipientEmails($recipients)
                ->subject($subject)
                ->body($body)
                ->attachments($attachments);
        return $factory;
    }
}
