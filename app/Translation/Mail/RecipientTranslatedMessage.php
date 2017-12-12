<?php

namespace App\Translation\Mail;

use App\Translation\Mail\Traits\TranslatedMail;
use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecipientTranslatedMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, TranslatedMail;

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
        $this->translatedMessage = $translatedMessage->load(['user', 'sourceLanguage']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->translatedMessage->auto_translate_reply) {
            $this->replyTo("reply_{$this->translatedMessage->hash}@in.bemail.io", $this->translatedMessage->user->name);
        } else {
            $this->replyTo($this->translatedMessage->user->email, $this->translatedMessage->user->name);
        }

        return $this->setSubject()
                    ->includeAttachments()
                    ->markdown('emails.translation.recipient-translated-message');
    }
}
