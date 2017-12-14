<?php


namespace App\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Message;
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
     * Recipients in a comma-separated list.
     *
     * This is what we get from the compose form.
     *
     *
     * @var string
     */
    protected $formRecipients;

    /**
     * Array of Recipient models.
     *
     * @var array
     */
    protected $recipientIDs = [];

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
     * Email that sent the reply Message.
     *
     * @var string
     */
    protected $replyFromEmail;

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

        $factory->formRecipients = $request->recipients;
        $factory->attachments = $request->attachments;

        return $factory;
    }

    /**
     * Make a REPLY Message.
     *
     * @param Message $message
     * @return static
     */
    public static function makeReply(Message $message)
    {
        $factory = new static();

        $factory->autoTranslateReply = 1;
        $factory->sendToSelf = 0;

        // a reply will have flipped language pairs to the
        // original message.
        $factory->langSrcId = $message->lang_tgt_id;
        $factory->langTgtId = $message->lang_src_id;

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
            // Reply from an email address
            $this->replyFromEmail = $user;
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
            'sender_email' => $this->replyFromEmail,
            'user_id' => $this->user->id,
            'translation_status_id' => TranslationStatus::available()->id,
            'lang_src_id' => $this->langSrcId,
            'lang_tgt_id' => $this->langTgtId
        ]);
        return $this;
    }

    /**
     * Check whether we should create Recipient(s).
     *
     * @return bool
     */
    protected function needToCreateRecipients()
    {
        return !$this->replyFromEmail && !$this->sendToSelf;
    }

    /**
     * Create Message Recipient(s).
     *
     * @return $this
     */
    protected function createRecipients()
    {
        $emails = explode(',', $this->formRecipients);
        foreach ($emails as $email) {
            $recipient = RecipientFactory::for ($this->messageModel)->to($email)->make();
            array_push($this->recipientIDs, $recipient->id);
        }
        $this->messageModel->recipients()->sync($this->recipientIDs);
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
        $method = AttachmentFactory::attachmentTypeMethodName($this->attachments[0]);
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
        if ($this->needToCreateRecipients()) $this->createRecipients();
        if ($this->hasAttachments()) $this->createAttachments();
        return $this->messageModel;
    }
}