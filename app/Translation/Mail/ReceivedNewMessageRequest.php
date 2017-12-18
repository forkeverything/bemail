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
            'user',
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
        $messages = [
            $this->translationMessage,
            $this->translationMessage->originalMessage
        ];
        return $this->subject($subject)
        ->view('emails.messages.html.received-new-message-request', compact('messages'))
        ->text('emails.messages.text.received-new-message-request', compact('messages'));

    }
}
