<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Notifies the sender that the email has been translated and sent.
 * Sent for messages with recipients.
 *
 * Class MessageTranslatedNotification
 * @package App\Translation\Mail
 */
class MessageTranslatedNotification extends Mailable
{
    use Queueable, SerializesModels, SendsTranslatedMessage;

    /**
     * @var Message
     */
    public $translatedMessage;

    /**
     * @var Collection
     */
    public $threadMessages;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->translatedMessage = $message->load(['recipients', 'sourceLanguage', 'targetLanguage']);
        $this->threadMessages = $this->translatedMessage->thread()->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->translatedMessage->subject ? 'SENT : ' . $this->translatedMessage->subject : "MESSAGE SENT";
        return $this->subject($subject)
            ->includeAttachments()
            ->view('emails.translation.html.message-translated-notification')
            ->text('emails.translation.text.message-translated-notification');

    }
}
