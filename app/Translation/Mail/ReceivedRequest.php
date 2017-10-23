<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceivedRequest extends Mailable implements ShouldQueue
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
        $this->translationMessage = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.translation.received-request');
    }
}
