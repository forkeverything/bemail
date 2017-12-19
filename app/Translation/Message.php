<?php

namespace App\Translation;

use App\Language;
use App\Payments\MessageReceipt;
use App\Traits\Hashable;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    use Hashable;

    /**
     * Mass-fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'body',
        'translated_body',
        'auto_translate_reply',
        'send_to_self',
        'user_id',
        'reply_id',
        'translation_status_id',
        'lang_src_id',
        'lang_tgt_id',
    ];

    /**
     * Automatically appended dynamic properties.
     *
     * @var array
     */
    protected $appends = [
        'hash',
        'word_count',
        'has_recipients',
        'readable_created_at'
    ];

    /**
     * Automatically turn these properties into Carbon instances.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    /**
     * User that owns this Message.
     * This is different from the sender. This is the User that is
     * gets charged for the message and reply messages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Recipient(s) that this message will be sent to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients()
    {
        return $this->hasMany(Recipient::class);
    }

    /**
     * Reply that this Message is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function intendedReply()
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }

    /**
     * Message could have many replies each with their own Message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'original_message_id');
    }

    /**
     * Check whether Message
     *
     * @return bool
     */
    public function isReply()
    {
        return !! $this->reply_id;
    }

    /**
     * Sender email.
     *
     * @return mixed
     */
    public function senderEmail()
    {
        return $this->isReply() ? $this->intendedReply->sender_email : $this->owner->email;
    }

    /**
     * Sender Name
     *
     * @return mixed
     */
    public function senderName()
    {
        return $this->isReply() ? $this->intendedReply->sender_name : $this->owner->name;
    }

    /**
     * Attachment(s) User might want to send with the Message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Status of Message translation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(TranslationStatus::class, 'translation_status_id');
    }

    /**
     * Language originally written in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sourceLanguage()
    {
        return $this->belongsTo(Language::class, 'lang_src_id');
    }

    /**
     * Language to be translated into.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function targetLanguage()
    {
        return $this->belongsTo(Language::class, 'lang_tgt_id');
    }

    /**
     * MessageReceipt that shows cost break-down for Message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receipt()
    {
        return $this->hasOne(MessageReceipt::class, 'message_id');
    }

    /**
     * How many words to translate?
     *
     * @return mixed
     */
    public function getWordCountAttribute()
    {
        return str_word_count($this->body);
    }

    /**
     * Has Recipient(s) attached?
     *
     * @return bool
     */
    public function getHasRecipientsAttribute()
    {
        return $this->recipients->count() > 0;
    }

    /**
     * 'created_at' field date formatted for readability'
     *
     * @return string
     */
    public function getReadableCreatedAtAttribute()
    {
        // 'Jan 1, 05:23 UTC'
        return $this->created_at->format('M j, H:i e');
    }

    /**
     * Error when trying to translate Message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function error()
    {
        return $this->hasOne(MessageError::class, 'message_id');
    }

    /**
     * Updates the Message Status.
     *
     * @param TranslationStatus $status
     */
    public function updateStatus(TranslationStatus $status)
    {
        $this->update([
            'translation_status_id' => $status->id
        ]);
    }

}
