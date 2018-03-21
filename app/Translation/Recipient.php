<?php

namespace App\Translation;

use App\Translation\Recipient\RecipientType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\Recipient
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $email
 * @property int $recipient_type_id
 * @property int $message_id
 * @property-read \App\Translation\Message $message
 * @property-read \App\Translation\Recipient\RecipientType $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient bcc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient cc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient standard()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereRecipientTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Recipient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Recipient extends Model
{
    protected $fillable = [
        'email',
        'recipient_type_id',
        'message_id'
    ];

    /**
     * RecipientType of PostmarkInboundMailRecipient: standard, cc, bcc
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
     * Message that this PostmarkInboundMailRecipient received.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * 'standard' Recipients
     *
     * @param $query
     * @return mixed
     */
    public function scopeStandard($query)
    {
        return $query->where('recipient_type_id', RecipientType::standard()->id);
    }

    /**
     * 'cc' Recipients
     *
     * @param $query
     * @return mixed
     */
    public function scopeCc($query)
    {
        return $query->where('recipient_type_id', RecipientType::cc()->id);
    }

    /**
     * 'bcc' Recipients
     *
     * @param $query
     * @return mixed
     */
    public function scopeBcc($query)
    {
        return $query->where('recipient_type_id', RecipientType::bcc()->id);
    }
}
