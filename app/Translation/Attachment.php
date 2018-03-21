<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\Attachment
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $file_name
 * @property string $original_file_name
 * @property string $path
 * @property int $size
 * @property int $message_id
 * @property-read \App\Translation\Message $message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Attachment whereUpdatedAt($value)
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
