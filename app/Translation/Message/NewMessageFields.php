<?php

namespace App\Translation\Message;

use App\Http\Requests\CreateMessageRequest;
use App\Language;


/**
 * The required fields for a new Message.
 *
 * @package App\Translation\Message
 */
class NewMessageFields
{

    /**
     * @var CreateMessageRequest $request
     */
    private $request;

    /**
     * What the message is for.
     *
     *
     * @var string
     */
    public $subject;
    /**
     * The text to be translated.
     *
     * @var string
     */
    public $body;
    /**
     * Translate replies from recipients?
     *
     * @var bool
     */
    public $autoTranslateReply;
    /**
     * Don't send to recipients but back to sender instead?
     *
     * @var bool
     */
    public $sendToSelf;
    /**
     * Language Id of the Language to translate from.
     *
     * @var int
     */
    public $langSrcId;
    /**
     * Language Id of the Language to translate to.
     *
     * @var int
     */
    public $langTgtId;
    /**
     * List of emails to send the Message to in a comma separated string.
     *
     * @var string
     */
    public $recipients;
    /**
     * Attachment files to send with Message.
     *
     * @var array
     */
    public $attachments;

    /**
     * Create NewMessageFields instance.
     *
     * @param CreateMessageRequest $request
     */
    public function __construct(CreateMessageRequest $request)
    {
        $this->request = $request;

        $this->setSubject()
             ->setBody()
             ->setAutoTranslateReply()
             ->setSendToSelf()
             ->setLangSrcId()
             ->setLangTgtId()
             ->setRecipients()
             ->setAttachments();
    }

    /**
     * Set subject field.
     *
     * @return $this
     */
    public function setSubject()
    {
        $this->subject = $this->request->subject;
        return $this;
    }

    /**
     * Set body field.
     *
     * @return $this
     */
    public function setBody()
    {
        $this->body = $this->request->body;
        return $this;
    }

    /**
     * Set the auto translate reply field.
     *
     * @return $this
     */
    public function setAutoTranslateReply()
    {
        $this->autoTranslateReply = !!$this->request->auto_translate_reply;
        return $this;
    }

    /**
     * Set the send to self field.
     *
     * @return $this
     */
    public function setSendToSelf()
    {
        $this->sendToSelf = !!$this->request->send_to_self;
        return $this;
    }

    /**
     * Set the source language ID.
     *
     * @return $this
     */
    public function setLangSrcId()
    {
        $this->langSrcId = Language::findByCode($this->request->lang_src)->id;
        return $this;
    }

    /**
     * Set the target language ID.
     *
     * @return $this
     */
    public function setLangTgtId()
    {
        $this->langTgtId = Language::findByCode($this->request->lang_tgt)->id;
        return $this;
    }

    /**
     * Set recipients list.
     *
     * @return $this
     */
    public function setRecipients()
    {
        $this->recipients = $this->request->recipients;
        return $this;
    }

    /**
     * Set attachments array.
     *
     * @return $this
     */
    public function setAttachments()
    {
        $this->attachments = $this->request->attachments;
        return $this;
    }


}