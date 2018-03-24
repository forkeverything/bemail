<?php

namespace App\Translation\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OriginalMessageNotFoundNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The reply email's body that wasn't sent.
     *
     * @var string
     */
    public $replyBody;

    /**
     * Create a new message instance.
     *
     * @param string $replyBody
     */
    public function __construct($replyBody)
    {
        $this->replyBody = $replyBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.messages.html.original-message-not-found-notification')
                    ->text('emails.messages.text.original-message-not-found-notification');
    }
}
