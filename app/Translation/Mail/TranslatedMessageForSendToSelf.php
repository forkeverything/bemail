<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Mail for the Sender when Message has been translated.
 * This is the email that's sent when the 'send_to_self' option
 * is enabled.
 *
 * @package App\Translation\Mail
 */
class TranslatedMessageForSendToSelf extends Mailable
{
    use Queueable, SerializesModels, SendsTranslatedMessage;

    /**
     * Message that has been translated.
     *
     * @var Message
     */
    public $translatedMessage;

    /**
     * Message(s) to be included in messages thread.
     *
     * @var Collection
     */
    public $threadMessages;

    /**
     * Create a new message instance.
     *
     * @param Message $translatedMessage
     */
    public function __construct(Message $translatedMessage)
    {
        $this->translatedMessage = $translatedMessage;
        $this->threadMessages = $this->translatedMessage->thread()->get();
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
                    ->view('emails.translation.html.translated-message-for-send-to-self')
                    ->text('emails.translation.text.translated-message-for-send-to-self');
    }
}
