<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Notifies the sender that the email has been translated and sent.
 * Sent for messages with recipients. For Messages without
 * recipients (send-to-self), the SenderTranslatedMessage
 * mail will be sent.
 *
 * Class NotifyMessageTranslated
 * @package App\Mail\Translation\Mail
 */
class NotifyMessageTranslated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $translatedMessage;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->translatedMessage = $message->load(['recipients', 'sourceLanguage', 'targetLanguage']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->translatedMessage->subject ? 'Translated and Sent: ' . $this->translatedMessage->subject : "Translated and Sent Message";
        return $this->subject($subject)
                    ->markdown('emails.translation.message-sent');
    }
}
