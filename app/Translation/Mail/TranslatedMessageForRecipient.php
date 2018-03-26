<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslatedMessageForRecipient extends Mailable
{
    use Queueable, SerializesModels, SendsTranslatedMessage;

    /**
     * Message that has been translated.
     *
     * @var Message
     */
    public $translatedMessage;

    /**
     * @var Collection
     */
    public $threadMessages;

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
        $this->threadMessages = $this->translatedMessage->thread()->get();
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
