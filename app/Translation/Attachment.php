<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\Attachment
 *
 * @property-read \App\Translation\Message $message
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    /**
     * Mass-fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'file_name',
        'original_file_name',
        'path',
        'size',
        'message_id'
    ];

    /**
     * Message this Attachment was attached to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

}
