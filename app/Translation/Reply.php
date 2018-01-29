<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * Reply class
 *
 * This class encapsulates a Message when it is a reply to another Message.
 * This is done because some senders might not have accounts and we need
 * to store sender info consistently.
 *
 * @package App\Translation
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
}
