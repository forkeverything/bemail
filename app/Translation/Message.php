<?php

namespace App\Translation;

use App\Language;
use App\Payments\MessageReceipt;
use App\Traits\Hashable;
use App\Translation\Contracts\AttachmentFile;
use App\Translation\Factories\AttachmentFactory;
use App\Translation\Factories\RecipientFactory;
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
 * @property int $auto_translate_reply
 * @property int $send_to_self
 * @property int $user_id
 * @property int|null $reply_id
 * @property int $lang_src_id
 * @property int $lang_tgt_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Attachment[] $attachments
 * @property-read \App\Translation\MessageError $error
 * @property-read bool $has_recipients
 * @property-read mixed $hash
 * @property-read string $readable_created_at
 * @property-read mixed $word_count
 * @property-read \App\User $owner
 * @property-read \App\Translation\Reply|null $parentReplyClass
 * @property-read \App\Payments\MessageReceipt $receipt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Recipient[] $recipients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Reply[] $replies
 * @property-read \App\Language $sourceLanguage
 * @property-read \App\Language $targetLanguage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereAutoTranslateReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereLangSrcId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereLangTgtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereReplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereSendToSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereTranslatedBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $gengo_order_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereGengoOrderId($value)
 * @property-read \App\Translation\Order $order
 */
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
        'lang_src_id',
        'lang_tgt_id'
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
    public function parentReplyClass()
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
        return !!$this->reply_id;
    }

    /**
     * Sender email.
     *
     * @return mixed
     */
    public function senderEmail()
    {
        return $this->isReply() ? $this->parentReplyClass->sender_email : $this->owner->email;
    }

    /**
     * Sender Name
     *
     * @return mixed
     */
    public function senderName()
    {
        return $this->isReply() ? $this->parentReplyClass->sender_name : $this->owner->name;
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
     * The translation Order for this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'message_id');
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
     * Translated body text.
     *
     * @param null $text
     * @return bool|null|string
     */
    public function translatedBody($text = null)
    {
        if (is_null($text)) {
            return $this->translated_body;
        }
        return $this->update([
            'translated_body' => $text
        ]);
    }

    /**
     * Create new recipient for this Message.
     *
     * @param RecipientType $type
     * @param $email
     * @return RecipientFactory
     */
    public function newRecipient(RecipientType $type, $email)
    {
        $factory = new RecipientFactory($this, $type, $email);
        return $factory;
    }

    /**
     * Create an attachment for this Message.
     *
     * @param AttachmentFile $attachment
     * @return AttachmentFactory
     */
    public function newAttachment(AttachmentFile $attachment)
    {
        $factory = new AttachmentFactory($this, $attachment);
        return $factory;
    }

    /**
     * Thread of Message(s) in a collection, starting with
     * this Message.
     *
     * @return MessageThread
     */
    public function thread()
    {
        return new MessageThread($this);
    }

    /**
     * Create a new translation Order.
     *
     * @param $orderId
     * @return $this|Model
     */
    public function createOrder($orderId)
    {
        return Order::createForMessage($this, $orderId);
    }

}
