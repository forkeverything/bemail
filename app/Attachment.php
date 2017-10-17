<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Attachment
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $file_name
 * @property string $original_file_name
 * @property string $path
 * @property int $size
 * @property int $message_id
 * @property-read \App\Message $message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereUpdatedAt($value)
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
