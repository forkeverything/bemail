<?php

namespace App\Mail\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class TranslatedMessageMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Message that has been translated.
     *
     * @var Message
     */
    public  $message;

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
        $this->message = $message->load([
            'owner',
            'sourceLanguage'
        ]);
        $this->threadMessages = $this->message->thread();
    }

    /**
     * Sets the subject of the message.
     *
     * @return $this
     */
    protected function setSubject()
    {
        $this->subject($this->message->subject);
        return $this;
    }

    /**
     * Include Attachment(s).
     * These were included when the Message was
     * originally composed.
     *
     * @return $this
     */
    protected function includeAttachments()
    {
        foreach ($this->message->attachments as $attachment) {
            $this->attach($attachment->path, [
                'as' => $attachment->original_file_name
            ]);
        }
        return $this;
    }

    /**
     * Build the message.
     *
     * @return void
     */
    abstract public function build();
}
