<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    /**
     * Mass-fillable fields
     *
     * @var array
     */
    protected $fillable = [
       'recipient',
        'subject',
        'body',
        'translated_body',
        'user_id',
        'translation_status_id'
    ];

    /**
     * Status of message translation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function translationStatus()
    {
        return $this->belongsTo(TranslationStatus::class, 'translation_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function translated()
    {
        return !! $this->translated_message;
    }
}
