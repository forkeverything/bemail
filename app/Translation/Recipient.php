<?php

namespace App\Translation;

use App\Translation\Recipient\RecipientType;
use Illuminate\Database\Eloquent\Model;

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
