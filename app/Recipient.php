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
