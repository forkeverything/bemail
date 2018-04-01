<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $this->message = $message;
        $this->threadMessages = $this->message->thread();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Message Could Not Be Translated")
            ->view('emails.translation.html.message-not-translated-due-to-system-error-notification')
            ->text('emails.translation.text.message-not-translated-due-to-system-error-notification');
    }
}
