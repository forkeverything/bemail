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
        $this->setSubject($request)
             ->setBody($request)
             ->setAutoTranslateReply($request)
             ->setSendToSelf($request)
             ->setLangSrcId($request)
             ->setLangTgtId($request)
             ->setRecipients($request)
             ->setAttachments($request);
    }

    /**
     * Set subject field.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setSubject(CreateMessageRequest $request)
    {
        $this->subject = $request->subject;
        return $this;
    }

    /**
     * Set body field.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setBody(CreateMessageRequest $request)
    {
        $this->body = $request->body;
        return $this;
    }

    /**
     * Set the auto translate reply field.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setAutoTranslateReply(CreateMessageRequest $request)
    {
        $this->autoTranslateReply = !!$request->auto_translate_reply;
        return $this;
    }

    /**
     * Set the send to self field.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setSendToSelf(CreateMessageRequest $request)
    {
        $this->sendToSelf = !!$request->send_to_self;
        return $this;
    }

    /**
     * Set the source language ID.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setLangSrcId(CreateMessageRequest $request)
    {
        $this->langSrcId = Language::findByCode($request->lang_src)->id;
        return $this;
    }

    /**
     * Set the target language ID.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setLangTgtId(CreateMessageRequest $request)
    {
        $this->langTgtId = Language::findByCode($request->lang_tgt)->id;
        return $this;
    }

    /**
     * Set recipients list.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setRecipients(CreateMessageRequest $request)
    {
        $this->recipients = $request->recipients;
        return $this;
    }

    /**
     * Set attachments array.
     *
     * @param CreateMessageRequest $request
     * @return $this
     */
    public function setAttachments(CreateMessageRequest $request)
    {
        $this->attachments = $request->attachments;
        return $this;
    }


}