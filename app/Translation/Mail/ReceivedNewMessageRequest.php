<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceivedNewMessageRequest extends Mailable implements ShouldQueue
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
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        // Eager-load relations
        $this->translationMessage = $message->load([
            'recipients',
            'sourceLanguage',
            'targetLanguage',
            'receipt.creditTransaction'
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->translationMessage->subject ? 'Translation Request: ' . $this->translationMessage->subject : "Received Translation Request";
        return $this->subject($subject)
            ->markdown('emails.translation.received-new-message-request');
    }
}
