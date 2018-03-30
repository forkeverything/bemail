<?php

namespace App\Translation\Mail;

use App\Mail\Translation\Mail\TranslatedMessageMailer;
use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslatedMessageForRecipient extends TranslatedMessageMailer
{

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        parent::__construct($message);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->autoTranslateReply()) {
            $this->setFromInboundReplyAddress();
        } else {
            $this->setFromOwnerAddress();
        }

        return $this->setSubject()
                    ->includeAttachments()
                    ->view('emails.translation.html.translated-message-for-recipient')
                    ->text('emails.translation.text.translated-message-for-recipient');
    }

    /**
     * Automatically translate any replies?
     *
     * @return bool
     */
    private function autoTranslateReply()
    {
        return !!$this->message->auto_translate_reply;
    }


    /**
     * Set from address to bemail's inbound reply address.
     *
     * @return $this
     */
    private function setFromInboundReplyAddress()
    {
        $this->from("reply_{$this->message->hash}@in.bemail.io", $this->message->owner->name);
        return $this;
    }

    /**
     * Set to the sender's own email address.
     *
     * @return $this
     */
    private function setFromOwnerAddress()
    {
        $this->from($this->message->owner->email, $this->message->owner->name);
        return $this;
    }
}
