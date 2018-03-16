<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceivedNewMessageRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Message that will be translated.
     * If we just named it $message, Laravel pulls the
     * wrong class (probably due to name conflict).
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
        // Eager-load relations
        $this->translationMessage = $message->load([
            'owner',
            'recipients',
            'sourceLanguage',
            'targetLanguage',
            'receipt.creditTransaction'
        ]);
        $this->messages = $this->translationMessage->thread();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->translationMessage->subject ? $this->translationMessage->subject : "New Translation Request";

        return $this->subject($subject)
                    ->view('emails.messages.html.received-new-message-request')
                    ->text('emails.messages.text.received-new-message-request');

    }
}
