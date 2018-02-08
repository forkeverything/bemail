<?php


namespace App\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Attachments\FormUploadedFile;
use App\Translation\Message;
use App\Translation\RecipientType;
use App\Translation\Reply;
use App\Translation\TranslationStatus;
use App\Translation\Utilities\AttachmentFileBuilder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * MessageFactory - Creates Messages (new and replies).
 *
 * @package App\Translation\Factories
 */
class MessageFactory
{

    /**
     * User that this Message belongs to.
     *
     * If this is a new message, then this User is also the
     * sender. Otherwise, if it's a reply, this User is
     * the one that owns (pays for) the messages.
     *
     * @var
     */
    protected $owner;
    /**
     * Compose Message form request.
     *
     * @var
     */
    protected $formRequest;
    /** Id of reply if Message is a reply */
    protected $replyId;
    /**
     * Newly created Message model.
     *
     * @var Message
     */
    protected $messageModel;
    /**
     * Recipient emails (string).
     *
     * @var array
     */
    protected $recipientEmails = [
        'standard' => [],
        'cc' => [],
        'bcc' => []
    ];
    /**
     * Email Subject
     *
     * @var string
     */
    protected $subject;
    /**
     * Email body
     *
     * @var string
     */
    protected $body;
    /**
     * Whether to allow auto-translated replies.
     *
     * @var boolean
     */
    protected $autoTranslateReply;
    /**
     * Whether to send email back to sender.
     *
     * For when the client wants to review the translated message
     * before manually sending it himself.
     *
     * @var
     */
    protected $sendToSelf;
    /**
     * Source Language ID
     *
     * @var int
     */
    protected $langSrcId;
    /**
     * Target Language ID
     *
     * @var int
     */
    protected $langTgtId;
    /**
     * File attachments to the Email.
     *
     * Array that holds instances of AttachmentFile
     *
     * @var array
     */
    protected $attachments = [];

    /**
     * Make a NEW Message.
     * New in this case meaning that the Message is NOT a reply to another
     * Message.
     *
     * @param CreateMessageRequest $request
     * @return static
     */
    public static function new(CreateMessageRequest $request)
    {
        $factory = new static();

        $factory->subject = $request->subject;
        $factory->body = $request->body;
        $factory->autoTranslateReply = !!$request->auto_translate_reply;
        $factory->sendToSelf = !!$request->send_to_self;
        $factory->langSrcId = Language::findByCode($request->lang_src)->id;
        $factory->langTgtId = Language::findByCode($request->lang_tgt)->id;
        $factory->recipientEmails["standard"] = explode(',', $request->recipients);
        if($request->attachments) $factory->attachments = AttachmentFileBuilder::convertArrayOfUploadedFiles($request->attachments);

        return $factory;
    }

    /**
     * Make a REPLY Message.
     *
     * @param Reply $reply
     * @return static
     */
    public static function reply(Reply $reply)
    {
        $factory = new static();

        // set reply id
        $factory->replyId = $reply->id;
        // Replies mean that original message had auto-translate 'on'
        // and consequently send-to-self 'off'.
        $factory->autoTranslateReply = 1;
        $factory->sendToSelf = 0;
        // Reply message share same owner as original.
        $factory->owner = $reply->originalMessage->owner;
        // a reply message will have flipped language pairs to the
        // original message.
        $factory->langSrcId = $reply->originalMessage->lang_tgt_id;
        $factory->langTgtId = $reply->originalMessage->lang_src_id;

        return $factory;
    }

    /**
     * Set subject.
     *
     * @param null $subject
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set body.
     *
     * @param $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set recipientEmails.
     *
     * @param $recipientEmails
     * @return $this
     */
    public function recipientEmails($recipientEmails)
    {
        $this->recipientEmails = $recipientEmails;
        return $this;
    }

    /**
     * Set attachments.
     *
     * @param $attachments
     * @return $this
     */
    public function attachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Set Message owner.
     *
     * @param User $user
     * @return $this
     */
    public function owner(User $user)
    {
        $this->owner = $user;
        return $this;
    }

    /**
     * Create Message model.
     *
     * @return $this
     */
    protected function createModel()
    {
        $this->messageModel = Message::create([
            'subject' => $this->subject,
            'body' => $this->body,
            'auto_translate_reply' => $this->autoTranslateReply,
            'send_to_self' => $this->sendToSelf,
            'user_id' => $this->owner->id,
            'reply_id' => $this->replyId,
            'translation_status_id' => TranslationStatus::available()->id,
            'lang_src_id' => $this->langSrcId,
            'lang_tgt_id' => $this->langTgtId
        ]);
        return $this;
    }

    /**
     * Create Message Recipient(s).
     *
     * @return $this
     */
    protected function createRecipients()
    {
        foreach ($this->recipientEmails as $type => $emails) {
            $recipientType = RecipientType::findType($type);
            foreach ($emails as $email) {
                RecipientFactory::for ($this->messageModel)->type($recipientType)->to($email)->make();
            }
        }

        // Manually create Recipient (original sender) when message is
        // a reply because the reply address is bemail's inbound
        // address.
        if ($this->messageModel->isReply()) {
            $originalMessageSenderEmail = $this->messageModel->parentReplyClass->originalMessage->senderEmail();
            RecipientFactory::for($this->messageModel)->type(RecipientType::standard())->to($originalMessageSenderEmail)->make();
        }

        return $this;
    }

    /**
     * Check whether Message has attachments.
     *
     * @return bool
     */
    protected function hasAttachments()
    {
        return $this->attachments && count($this->attachments) > 0;
    }

    /**
     * Create the Attachment(s) to this Message.
     *
     */
    protected function createAttachments()
    {
        foreach ($this->attachments as $attachment) {
            AttachmentFactory::from($attachment)->for($this->messageModel)->make();
        }
    }

    /**
     * Make our Message.
     *
     * @return Message
     */
    public function make()
    {
        $this->createModel();
        if (!$this->sendToSelf) $this->createRecipients();
        if ($this->hasAttachments()) $this->createAttachments();
        return $this->messageModel;
    }
}