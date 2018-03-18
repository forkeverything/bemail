<?php


namespace App\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Attachments\FormUploadedFile;
use App\Translation\Contracts\AttachmentFile;
use App\Translation\Factories\MessageFactory\RecipientEmails;
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
     * This User is the same for every Message on the Message
     * chain and will also be the User that is charged
     * for each reply.
     *
     * @var User
     */
    protected $owner;
    /**
     * Compose Message form request.
     *
     * @var
     */
    protected $formRequest;
    /**
     * Original Message ID for a reply Message.
     *
     * @var int|null
     */
    protected $messageId;
    /**
     * Newly created Message model.
     *
     * @var Message
     */
    protected $messageModel;
    /**
     * Recipient emails
     *
     * @var RecipientEmails
     */
    protected $recipientEmails;
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
     * @var bool
     */
    protected $sendToSelf;
    /**
     * Email address of sender.
     *
     * @var string
     */
    protected $senderEmail;
    /**
     * Name of sender.
     * @var string|null
     */
    protected $senderName;
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
     * New MessageFactory for a new Message from given User.
     *
     * @param User $user
     * @return static
     */
    public static function newMessageFromUser(User $user)
    {
        $factory = new static();
        $factory->senderEmail = $user->email;
        $factory->senderName = $user->name;
        $factory->owner = $user;
        return $factory;
    }

    /**
     * New MessageFactory to make a reply Message.
     *
     * @param Message $originalMessage
     * @return static
     */
    public static function newReplyToMessage(Message $originalMessage)
    {
        $factory = new static();
        $factory->messageId = $originalMessage->id;
        // Replies mean that original message had auto-translate 'on'
        // and consequently send-to-self 'off'.
        $factory->autoTranslateReply = 1;
        $factory->sendToSelf = 0;
        // Reply message share same owner as original.
        $factory->owner = $originalMessage->owner;
        // a reply message will have flipped language pairs to the
        // original message.
        $factory->langSrcId = $originalMessage->lang_tgt_id;
        $factory->langTgtId = $originalMessage->lang_src_id;
        return $factory;
    }

    /**
     * Create Message Recipient(s).
     *
     * @return $this
     */
    protected function createRecipients()
    {
        foreach ($this->recipientEmails->all() as $type => $emails) {
            $recipientType = RecipientType::findByName($type);
            foreach ($emails as $email) {
                $this->messageModel->newRecipient($recipientType, $email)->make();
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
        foreach ($this->attachments as $attachment) {
            $this->messageModel->newAttachment($attachment)->make();
        }
    }

    /**
     * Set sender email property.
     *
     * @param $email
     * @return $this
     */
    public function setSenderEmail($email)
    {
        $this->senderEmail = $email;
        return $this;
    }

    /**
     * Set sender name property.
     *
     * @param $name
     * @return $this
     */
    public function setSenderName($name)
    {
        $this->senderName = $name;
        return $this;
    }

    /**
     * Set recipientEmails.
     *
     * @param RecipientEmails $recipientEmails
     * @return $this
     */
    public function setRecipientEmails($recipientEmails)
    {
        $this->recipientEmails = $recipientEmails;
        return $this;
    }

    /**
     * Set subject.
     *
     * @param null $subject
     * @return $this
     */
    public function setSubject($subject)
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
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set autoTranslateReply.
     *
     * @param bool $autoTranslate
     * @return $this
     */
    public function setAutoTranslateReply($autoTranslate)
    {
        $this->autoTranslateReply = $autoTranslate;
        return $this;
    }

    /**
     * Set sendToSelf.
     * 
     * @param $sendToSelf
     * @return $this
     */
    public function setSendToSelf($sendToSelf)
    {
        $this->sendToSelf = $sendToSelf;
        return $this;
    }

    /**
     * Set source language id.
     * 
     * @param $id
     * @return $this
     */
    public function setLangSrcId($id)
    {
        $this->langSrcId = $id;
        return $this;
    }

    /**
     * Set target language id.
     *
     * @param $id
     * @return $this
     */
    public function setLangTgtId($id)
    {
        $this->langTgtId = $id;
        return $this;
    }

    /**
     * Set attachments.
     *
     * @param $attachments
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
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
            'message_id' => $this->messageId,
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