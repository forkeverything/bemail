<?php


namespace App\Translation\Message;


use App\Contracts\InboundMail\InboundMailRecipient;
use App\Contracts\InboundMail\InboundMailRequest;
use App\Translation\Factories\AttachmentFactory\PostmarkAttachmentFile;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\Recipient\RecipientType;

/**
 * Class ReplyMessageBuilder
 *
 * Builds models for a reply Message.
 *
 * @package App\Translation\Message
 */
class ReplyMessageBuilder extends MessageBuilder
{
    /**
     * @var InboundMailRequest
     */
    private $request;
    /**
     * @var Message
     */
    private $originalMessage;

    /**
     * Create ReplyMessageBuilder instance.
     * @param InboundMailRequest $request
     * @param Message $originalMessage
     */
    public function __construct(InboundMailRequest $request, Message $originalMessage)
    {
        $this->request = $request;
        $this->originalMessage = $originalMessage;
    }

    /**
     * Turn reply email into Message.
     *
     * @return $this
     */
    public function buildMessage()
    {
        $this->message = $this->originalMessage->newReply()
                                               ->senderEmail($this->request->fromAddress())
                                               ->senderName($this->request->fromName())
                                               ->subject($this->request->subject())
                                               ->body($this->request->strippedReplyBody())
                                               ->make();
        return $this;
    }

    /**
     * Take recipients from reply email and creates Recipient(s).
     *
     * @return $this
     * @throws \Exception
     */
    public function buildRecipients()
    {

        $this->checkForMessageBeforeBuildingRecipients();

        $this->message->newRecipients()
                ->recipientEmails($this->recipientEmailsFromInboundMailRequest())
                ->make();
        return $this;
    }

    /**
     * Create Attachment(s) from any email attachments.
     *
     * @return $this
     * @throws \Exception
     */
    public function buildAttachments()
    {

        $this->checkForMessageBeforeBuildingAttachments();

        $this->message->newAttachments()
                ->attachmentFiles(PostmarkAttachmentFile::convertArray($this->request->attachments()))
                ->make();

        return $this;
    }

    /**
     * Creates RecipientEmails.
     *
     * @return RecipientEmails
     */
    private function recipientEmailsFromInboundMailRequest()
    {

        $recipientEmails = RecipientEmails::new();

        $types = [
            'standard',
            'cc',
            'bcc'
        ];


        foreach ($types as $type) {
            $recipients = call_user_func([$this->request, "{$type}Recipients"]);
            foreach ($recipients as $inboundMailRecipient) {
                /**
                 * @var $inboundMailRecipient InboundMailRecipient
                 */
                $recipientEmails->addEmailToType($inboundMailRecipient->email(), call_user_func("App\Translation\Recipient\RecipientType::{$type}"));
            }
        }

        // Manually add original Message sender email as recipient because
        // the reply address is bemail's inbound address.
        $recipientEmails->addEmailToType($this->originalMessage->sender_email, RecipientType::standard());

        return $recipientEmails;
    }

}