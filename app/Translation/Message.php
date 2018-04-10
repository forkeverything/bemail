<?php

namespace App\Translation;

use App\Error;
use App\Language;
use App\Payment\Receipt;
use App\Traits\Hashable;
use App\Contracts\Translation\AttachmentFile;
use App\Translation\Factories\AttachmentFactory;
use App\Translation\Factories\MessageFactory;
use App\Translation\Factories\RecipientFactory;
use App\Translation\Message\MessageThread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
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
 * @property string $sender_email
 * @property string $sender_name
 * @property int $user_id
 * @property int|null $message_id
 * @property int $lang_src_id
 * @property int $lang_tgt_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Attachment[] $attachments
 * @property-read bool $has_recipients
 * @property-read string $hash
 * @property-read string $readable_created_at
 * @property-read \App\Translation\Order $order
 * @property-read \App\Translation\Message|null $originalMessage
 * @property-read \App\User $owner
 * @property-read \App\Payment\Receipt $receipt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Recipient[] $recipients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Message[] $replies
 * @property-read \App\Language $sourceLanguage
 * @property-read \App\Language $targetLanguage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereAutoTranslateReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereLangSrcId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereLangTgtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereSendToSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereSenderEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereTranslatedBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Message whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Error $error
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
        'sender_email',
        'sender_name',
        'user_id',
        'message_id',
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
     * PostmarkInboundMailRecipient(s) that this message will be sent to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients()
    {
        return $this->hasMany(Recipient::class);
    }

    /**
     * The Message that this was replying to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function originalMessage()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * All the Message(s) that are replies to this Message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'message_id');
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
     * The Receipt with Message payment info.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receipt()
    {
        return $this->hasOne(Receipt::class, 'message_id');
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
     * Check whether Message
     *
     * @return bool
     */
    public function isReply()
    {
        return !!$this->message_id;
    }

    /**
     * Has PostmarkInboundMailRecipient(s) attached?
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
     * The reason why a Message wasn't handled properly.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function error()
    {
        return $this->morphOne(Error::class, 'errorable');
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
     * Add PostmarkInboundMailRecipient(s).
     *
     * @return RecipientFactory
     */
    public function newRecipients()
    {
        return new RecipientFactory($this);
    }


    /**
     * Add Attachment(s).
     *
     * @param AttachmentFile $attachment
     * @return AttachmentFactory
     */
    public function newAttachments()
    {
        $factory = new AttachmentFactory($this);
        return $factory;
    }

    /**
     * MessageThread of Message(s) in a collection, starting with
     * this Message.
     *
     * @return Collection
     */
    public function thread()
    {
        return (new MessageThread($this))->get();
    }

    /**
     * Instantiate a new translation Order.
     *
     * @return Order
     */
    public function newOrder()
    {
        return Order::newForMessage($this);
    }

    /**
     * Instantiate a new payment Receipt.
     *
     * @return Receipt
     */
    public function newReceipt()
    {
        return Receipt::newForMessage($this);
    }

    /**
     * Make a new reply Message.
     *
     * @return MessageFactory
     */
    public function newReply()
    {
        return MessageFactory::newReplyToMessage($this);
    }

    /**
     * Instantiate a new Error for this Message.
     *
     * @return Error
     */
    public function newError()
    {
        return Error::newForMessage($this);
    }

    /**
     * Is the sender also the owner?
     *
     * @return bool
     */
    public function senderIsTheOwner()
    {
        return $this->sender_email === $this->owner->email;
    }

}
