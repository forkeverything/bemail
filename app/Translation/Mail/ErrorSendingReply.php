<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorSendingReply extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $message;

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
        $this->message = $message;
        $this->messages = $message->thread()->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->message->subject ? 'Error Sending Reply: ' . $this->message->subject : "Error Sending Reply";

        return $this->subject($subject)
                    ->view('emails.messages.html.error-sending-reply')
                    ->text('emails.messages.text.error-sending-reply');
    }
}
