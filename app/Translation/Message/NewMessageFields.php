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
     * @var CreateMessageRequest
     */
    private $request;

    /**
     * Create NewMessageFields instance.
     *
     * @param CreateMessageRequest $request
     */
    public function __construct(CreateMessageRequest $request)
    {
        $this->request = $request;
    }

    /**
     * What the message is for.
     *
     * @return string
     */
    public function subject()
    {
        return $this->request->subject;
    }

    /**
     * The text to be translated.
     *
     * @return string
     */
    public function body()
    {
        return $this->request->body;
    }

    /**
     * Translate replies from recipients?
     *
     * @return bool
     */
    public function autoTranslateReply()
    {
        return !! $this->request->auto_translate_reply;
    }

    /**
     * Don't send to recipients but back to sender instead?
     *
     * @return bool
     */
    public function sendToSelf()
    {
        return !!$this->request->send_to_self;
    }

    /**
     * Language Id of the Language to translate from.
     *
     * @return int
     */
    public function langSrcId()
    {
        return Language::findByCode($this->request->lang_src)->id;
    }

    /**
     * Language Id of the Language to translate to.
     *
     * @return int
     */
    public function langTgtId()
    {
        return Language::findByCode($this->request->lang_tgt)->id;
    }

    /**
     * List of emails to send the Message to in a comma separated string.
     *
     * @return string
     */
    public function recipients()
    {
        return $this->request->recipients;
    }

    /**
     * Attachment files to send with Message.
     *
     * @return null|array
     */
    public function attachments()
    {
        return $this->request->attachments;
    }


}