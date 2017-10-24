<?php

namespace App\Mail\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecipientTranslatedMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $translatedMessage;

    /**
     * Create a new message instance.
     *
     * @param Message $translatedMessage
     */
    public function __construct(Message $translatedMessage)
    {
        $this->translatedMessage = $translatedMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Set subject
        $subject = $this->translatedMessage->subject ? "Translated: {$this->translatedMessage->subject}" : "Translated Message";
        $this->subject($subject);

        // Attachments
        foreach ($this->translatedMessage->attachments as $attachment) {
            $this->attach($attachment->path, [
                'as' => $attachment->original_file_name
            ]);
        }

        // Set template
        $this->view('emails.translation.recipient-translated-message');

        return $this;
    }
}
