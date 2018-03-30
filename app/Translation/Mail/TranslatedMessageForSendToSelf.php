<?php

namespace App\Translation\Mail;

use App\Mail\Translation\Mail\TranslatedMessageMailer;
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
class TranslatedMessageForSendToSelf extends TranslatedMessageMailer
{
    use Queueable, SerializesModels;

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
        return $this->setSubject()
                    ->includeAttachments()
                    ->view('emails.translation.html.translated-message-for-send-to-self')
                    ->text('emails.translation.text.translated-message-for-send-to-self');
    }
}
