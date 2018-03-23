<?php


namespace App\InboundMail\Postmark;


use App\Translation\Events\FailedCreatingReply;
use App\Translation\Events\ReplyMessageCreated;
use App\Translation\Mail\OriginalMessageNotFoundNotification;
use App\Translation\Message;
use App\Translation\Message\ReplyMessageBuilder;
use Exception;
use Illuminate\Support\Facades\Mail;

trait CanHandleReplies
{

    /**
     * Handle an inbound mail that's replying to a Message.
     *
     * @param PostmarkInboundMailRequest $postmarkRequest
     * @throws Exception
     */
    protected function handleReply(PostmarkInboundMailRequest $postmarkRequest)
    {
        /**
         * The Message being replied to.
         *
         * @var Message $originalMessage
         */
        if (!$originalMessage = Message::findByHash($postmarkRequest->target())) {
            Mail::to($postmarkRequest->fromAddress())->send(new OriginalMessageNotFoundNotification());
            return;
        }

        try {
            // Purposely create models here, out of event listener, to
            // avoid serialization of closure error.
            $message = $this->buildReplyModels($postmarkRequest, $originalMessage);
        } catch (Exception $e) {
            event(new FailedCreatingReply());
            // Re-throw to skip dispatching ReplyMessageCreated event.
            throw $e;
        }

        event(new ReplyMessageCreated($message));
    }

    /**
     * Create needed models for a reply Message.
     *
     * @param PostmarkInboundMailRequest $postmarkRequest
     * @param Message $originalMessage
     * @return Message
     * @throws Exception
     */
    protected function buildReplyModels(PostmarkInboundMailRequest $postmarkRequest, Message $originalMessage)
    {
        $builder = new ReplyMessageBuilder($postmarkRequest, $originalMessage);
        return $builder->buildMessage()
                       ->buildRecipients()
                       ->buildAttachments()
                       ->message();
    }
}