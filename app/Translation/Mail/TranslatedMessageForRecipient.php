<?php

namespace App\Translation\Mail;

use App\Translation\Mail\Traits\TranslatedMail;
use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslatedMessageForRecipient extends Mailable
{
    use Queueable, SerializesModels, TranslatedMail;

    /**
     * Message(s) to be included in message thread.
     *
     * @var
     */
    public $messages;

    /**
     * Create a new message instance.
     *
     * @param Message $translatedMessage
     */
    public function __construct(Message $translatedMessage)
    {
        $this->translatedMessage = $translatedMessage->load([
            'owner',
            'sourceLanguage'
        ]);

        // Build thread
        $this->messages = $this->translatedMessage->thread()->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->translatedMessage->auto_translate_reply) {
            $this->from("reply_{$this->translatedMessage->hash}@in.bemail.io", $this->translatedMessage->owner->name);
        } else {
            $this->from($this->translatedMessage->owner->email, $this->translatedMessage->owner->name);
        }

        return $this->setSubject()
                    ->includeAttachments()
                    ->view('emails.translation.html.translated-message-for-recipient')
                    ->text('emails.translation.text.translated-message-for-recipient');
    }
}
