<?php


namespace App\InboundMail\Postmark;


use App\Contracts\InboundMail\InboundMailRequest;
use App\Contracts\InboundMail\ReplyHandler;
use App\Traits\LogsExceptions;
use App\Translation\Events\FailedCreatingReply;
use App\Translation\Events\ReplyMessageCreated;
use App\Translation\Mail\OriginalMessageNotFoundNotification;
use App\Translation\Message;
use App\Translation\Message\ReplyMessageBuilder;
use Exception;
use Illuminate\Support\Facades\Mail;

class PostmarkReplyHandler implements ReplyHandler
{

    use LogsExceptions;

    /**
     * @var InboundMailRequest
     */
    protected $request;

    /**
     * Handle a request for a reply.
     *
     * @param InboundMailRequest $request
     * @return void
     */
    public function handleRequest(InboundMailRequest $request)
    {
        $this->request = $request;

        $originalMessage = $this->originalMessage();

        if (is_null($originalMessage)) {
            $this->sendOriginalMessageNotFoundNotification();
            return;
        }

        $replyMessage = $this->replyMessage($originalMessage);

        if (is_null($replyMessage)) {
            event(new FailedCreatingReply());
            return;
        }

        event(new ReplyMessageCreated($replyMessage));
    }

    /**
     * Get the message being replied to.
     *
     * The Message hash is included in the inbound mail
     * address as the target part of the email.
     *
     *
     * @return \Illuminate\Database\Eloquent\Model|Message|null
     */
    public function originalMessage()
    {
        try {
            return Message::findByHash($this->request->target());
        } catch (Exception $e) {
            //
        }
    }

    /**
     * Tell reply sender that the reply was not sent
     * as the original message couldn't be found.
     *
     * @return void
     */
    public function sendOriginalMessageNotFoundNotification()
    {
        Mail::to($this->request->fromAddress())->send(new OriginalMessageNotFoundNotification($this->request->strippedReplyBody()));
    }


    /**
     * The reply Message and associated models.
     *
     * @param Message $originalMessage
     * @return Message|null
     */
    public function replyMessage(Message $originalMessage)
    {
        try {
            $builder = new ReplyMessageBuilder($this->request, $originalMessage);
            return $builder->buildMessage()
                           ->buildRecipients()
                           ->buildAttachments()
                           ->message();
        } catch (Exception $e) {
            $this->logException('FAILED_CREATING_REPLY_MODELS', $e);
        }
    }
}