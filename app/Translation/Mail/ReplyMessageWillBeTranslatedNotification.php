<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Notifies the sender of a reply to a Message that
 * their reply has been received and will be
 * translated and sent shortly.
 *
 * Class ReplyMessageWillBeTranslatedNotification
 * @package App\Translation\Mail
 */
class ReplyMessageWillBeTranslatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Message to be translated.
     *
     * @var Message
     */
    public $translationMessage;

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
        $this->translationMessage = $message->load([
            'recipients',
            'sourceLanguage',
            'targetLanguage'
        ]);

        $this->threadMessages = $this->translationMessage->thread();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->translationMessage->subject ?: 'Received Message Reply';
        return $this->subject($subject)
                    ->view('emails.translation.html.reply-message-will-be-translated-notification')
                    ->text('emails.translation.text.reply-message-will-be-translated-notification');
    }
}
