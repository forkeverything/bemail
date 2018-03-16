<?php


namespace App\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Attachments\FormUploadedFile;
use App\Translation\Contracts\AttachmentFile;
use App\Translation\Message;
use App\Translation\RecipientType;
use App\Translation\Reply;
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
    protected $attachments;

    /**
     * Converts an array of UploadedFile(s).
     *
     * @param $attachments
     * @return array
     */
    protected function convertUploadedFiles($attachments)
    {
        return array_map(function ($uploadedFile) {
            return new FormUploadedFile($uploadedFile);
        }, $attachments);
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
                $this->messageModel->newRecipient($recipientType, $email)->make();
            }
        }

        // Manually create Recipient (original sender) when message is
        // a reply because the reply address is bemail's inbound
        // address.
        if ($this->messageModel->isReply()) {
            $originalMessageSenderEmail = $this->messageModel->parentReplyClass->originalMessage->senderEmail();
            $this->messageModel->newRecipient(RecipientType::standard(), $originalMessageSenderEmail)->make();
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
            $this->messageModel->newAttachment($attachment)->make();
        }
    }

    /**
     * Set fields from a new message request.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setNewMessageRequest(CreateMessageRequest $request)
    {
        $this->subject = $request->subject;
        $this->body = $request->body;
        $this->autoTranslateReply = !!$request->auto_translate_reply;
        $this->sendToSelf = !!$request->send_to_self;
        $this->langSrcId = Language::findByCode($request->lang_src)->id;
        $this->langTgtId = Language::findByCode($request->lang_tgt)->id;
        $this->recipientEmails["standard"] = explode(',', $request->recipients);
        $attachments = $request->attachments ?: [];
        if (count($attachments) > 0) {
            $this->attachments = $this->convertUploadedFiles($attachments);
        } else {
            $this->attachments = [];
        }

        return $this;
    }

    /**
     * Set fields from a Reply.
     *
     * @param Reply $reply
     * @return $this
     */
    public function setReply(Reply $reply)
    {
        // set reply id
        $this->replyId = $reply->id;
        // Replies mean that original message had auto-translate 'on'
        // and consequently send-to-self 'off'.
        $this->autoTranslateReply = 1;
        $this->sendToSelf = 0;
        // Reply message share same owner as original.
        $this->owner = $reply->originalMessage->owner;
        // a reply message will have flipped language pairs to the
        // original message.
        $this->langSrcId = $reply->originalMessage->lang_tgt_id;
        $this->langTgtId = $reply->originalMessage->lang_src_id;

        return $this;
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
            'lang_src_id' => $this->langSrcId,
            'lang_tgt_id' => $this->langTgtId
        ]);
        return $this;
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