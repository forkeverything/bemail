<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Stripe\Collection;

class MessageNotTranslatedDueToSystemErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Message that will not be translated.
     *
     * @var Message
     */
    public $message;

    /**
     * Message thread with previous messages.
     *
     * @var Collection
     */
    public $messages;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->messages = $this->message->thread()->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.messages.html.message-not-translated-due-to-system-error-notification')
            ->text('emails.messages.text.message-not-translated-due-to-system-error-notification');
    }
}
