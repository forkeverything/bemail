<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $fillable = [
        'email',
        'user_id'
    ];

    /**
     * Recipient(s) can have multiple Message(s) sent to
     * them.
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
}
