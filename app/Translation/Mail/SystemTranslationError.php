<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SystemTranslationError extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $messageWithError;

    /**
     * Create a new message instance.
     *
     * @param Message $messageWithError
     */
    public function __construct(Message $messageWithError)
    {
        $this->messageWithError = $messageWithError->load([
            'error',
            'sender'
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.translation.system-translation-error');
    }
}
