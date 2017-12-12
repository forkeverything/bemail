<?php

namespace App\Translation\Mail;

use App\Translation\Mail\Traits\TranslatedMail;
use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Mail for the Sender when Message has been translated.
 * This is the email that's sent when the 'send_to_self' option
 * is enabled.
 *
 * @package App\Mail\Translation\Mail
 */
class SenderTranslatedMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, TranslatedMail;

    /**
     * @var Message
     */
    public $translatedMessage;

    /**
     * Create a new message instance.
     *
     * @param Message $translatedMessage
     */
    public function __construct(Message $translatedMessage)
    {
        $this->translatedMessage = $translatedMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->setSubject()
                    ->includeAttachments()
                    ->markdown('emails.translation.sender-translated-message');
    }
}
