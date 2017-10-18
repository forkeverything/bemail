<?php

namespace App\Translation;

use App\Language;
use App\Payments\MessageReceipt;
use App\User;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Translation\Message
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $subject
 * @property string $body
 * @property string|null $translated_body
 * @property int $user_id
 * @property int $lang_src_id
 * @property int $lang_tgt_id
 * @property int $translation_status_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Attachment[] $attachments
 * @property-read mixed $word_count
 * @property-read \App\Payments\MessageReceipt $receipt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Recipient[] $recipients
 * @property-read \App\User $sender
 * @property-read \App\Language $sourceLanguage
 * @property-read \App\Translation\TranslationStatus $status
 * @property-read \App\Language $targetLanguage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereLangSrcId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereLangTgtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereTranslatedBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereTranslationStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends Model
{

    /**
     * Mass-fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'body',
        'translated_body',
        'user_id',
        'translation_status_id',
        'lang_src_id',
        'lang_tgt_id'
    ];

    /**
     * Automatically appended dynamic properties.
     *
     * @var array
     */
    protected $appends = [
        'word_count'
    ];

    /**
     * User that sent this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Recipient(s) that this message will be sent to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients()
    {
        return $this->belongsToMany(Recipient::class);
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
     * Change TranslationStatus to error.
     *
     * @return $this
     */
    public function markError()
    {
        $this->update([
            'translation_status_id' => TranslationStatus::error()->id
        ]);
        return $this;
    }

}
