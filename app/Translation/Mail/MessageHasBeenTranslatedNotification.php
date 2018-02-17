<?php

namespace App\Translation\Mail;

use App\Translation\Mail\Traits\TranslatedMail;
use App\Translation\Message;
use App\Translation\Utilities\MessageThreadBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Notifies the sender that the email has been translated and sent.
 * Sent for messages with recipients.
 *
 * Class MessageHasBeenTranslatedNotification
 * @package App\Translation\Mail
 */
class MessageHasBeenTranslatedNotification extends Mailable
{
    use Queueable, SerializesModels, TranslatedMail;

    /**
     * @var Message
     */
    public $translatedMessage;

    /**
     * Messages to include in the messages thread.
     *
     * @var
     */
    public $messages;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->translatedMessage = $message->load(['recipients', 'sourceLanguage', 'targetLanguage']);
        $this->messages = MessageThreadBuilder::startingFrom($this->translatedMessage);
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
            ->includeAttachments()
            ->view('emails.messages.html.message-has-been-translated-notification')
            ->text('emails.messages.text.message-has-been-translated-notification');

    }
}
