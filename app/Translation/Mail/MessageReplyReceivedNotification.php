<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use App\Translation\Utilities\MessageThreadBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Notifies the sender of a reply to a Message that
 * their reply has been received and will be
 * translated and sent shortly.
 *
 * Class MessageReplyReceivedNotification
 * @package App\Translation\Mail
 */
class MessageReplyReceivedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Message to be translated.
     *
     * @var Message
     */
    public $translationMessage;

    /**
     * Message(s) to be included in message thread.
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
        $this->translationMessage = $message->load([
            'recipients',
            'sourceLanguage',
            'targetLanguage'
        ]);

        $this->messages = MessageThreadBuilder::startingFrom($this->translationMessage);
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
                    ->view('emails.messages.html.message-reply-received-notification')
                    ->text('emails.messages.text.message-reply-received-notification');
    }
}
