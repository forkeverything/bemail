<?php

namespace App\Contracts\InboundMail;

use App\Translation\Message;

interface ReplyHandler
{
    /**
     * Get the message being replied to.
     *
     * @return Message
     */
    public function originalMessage();

    /**
     * Tell reply sender that the reply was not sent
     * as the original message couldn't be found.
     *
     * @return void
     */
    public function sendOriginalMessageNotFoundNotification();

    /**
     * The reply Message and associated models.
     *
     * @param Message $originalMessage
     * @return Message|null
     */
    public function replyMessage(Message $originalMessage);

    /**
     * Handle a request for a reply.
     *
     * @param InboundMailRequest $request
     * @return void
     */
    public function handleRequest(InboundMailRequest $request);
}