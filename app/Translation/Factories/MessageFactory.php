<?php


namespace App\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Message;
use App\Translation\RecipientType;
use App\Translation\TranslationStatus;
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
    protected $user;
    /**
     * Compose Message form request.
     *
     * @var
     */
    protected $formRequest;
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
     * Email that sent the reply Message.
     *
     * @var string
     */
    protected $replySenderEmail;
    /**
     * ID of the original Message.
     * This would be set when we are creating a reply
     * Message.
     *
     * @var Message
     */
    protected $messageId = null;
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
     * Array that holds either instances of UploadedFile (from
     * form) or PostmarkAttachmentFile (from Postmark
     * inbound email).
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
    public static function makeNew(CreateMessageRequest $request)
    {
        $factory = new static();

        $factory->subject = $request->subject;
        $factory->body = $request->body;
        $factory->autoTranslateReply = !!$request->auto_translate_reply;
        $factory->sendToSelf = !!$request->send_to_self;
        $factory->langSrcId = Language::findByCode($request->lang_src)->id;
        $factory->langTgtId = Language::findByCode($request->lang_tgt)->id;
        $factory->recipientEmails["standard"] = explode(',', $request->recipients);
        $factory->attachments = $request->attachments;

        return $factory;
    }

    /**
     * Make a REPLY Message.
     *
     * @param Message $originalMessage
     * @return static
     */
    public static function makeReply(Message $originalMessage)
    {
        $factory = new static();

        $factory->messageId = $originalMessage->id;
        // Replies mean that original message had auto-translate 'on'
        // and consequently send-to-self 'off'.
        $factory->autoTranslateReply = 1;
        $factory->sendToSelf = 0;
        // A reply Message belongs to the same User that sent
        // the original Message
        $factory->user = $originalMessage->user;
        // a reply will have flipped language pairs to the
        // original message.
        $factory->langSrcId = $originalMessage->lang_tgt_id;
        $factory->langTgtId = $originalMessage->lang_src_id;

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
    public function recipientEmails($recipientEmails) {
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
     * Set who Message is from.
     *
     * @param $user
     * @return $this
     */
    public function from($user)
    {
        if ($user instanceof User) {
            // Sending a new message
            $this->user = $user;
        } else {
            // Email address of the sender that sent
            // the reply
            $this->replySenderEmail = $user;
        }
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
            'reply_sender_email' => $this->replySenderEmail,
            'user_id' => $this->user->id,
            'translation_status_id' => TranslationStatus::available()->id,
            'message_id' => $this->messageId,
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
            foreach($emails as $email) {
                RecipientFactory::for($this->messageModel)->type($recipientType)->to($email)->make();
            }
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
        $method = AttachmentFactory::resolveMethodFromAttachment($this->attachments[0]);
        foreach ($this->attachments as $attachment) {
            AttachmentFactory::$$method($attachment)->for($this->messageModel)->make();
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
        if (! $this->sendToSelf) $this->createRecipients();
        if ($this->hasAttachments()) $this->createAttachments();
        return $this->messageModel;
    }
}