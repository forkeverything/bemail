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
 * @package App\Translation\Mail
 */
class TranslatedMessageForSendToSelf extends Mailable
{
    use Queueable, SerializesModels, TranslatedMail;

    /**
     * Message(s) to be included in messages thread.
     *
     * @var
     */
    public $messages;

    /**
     * Create a new message instance.
     *
     * @param Message $translatedMessage
     */
    public function __construct(Message $translatedMessage)
    {
        $this->translatedMessage = $translatedMessage;
        $this->messages = $this->translatedMessage->thread()->get();
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
