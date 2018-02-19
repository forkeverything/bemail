<?php

namespace App\Translation\Events;

use App\Translation\Contracts\Translator;
use App\Translation\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReplyReceived
{
    use Dispatchable, SerializesModels;

    /**
     * @var string
     */
    public $fromAddress;
    /**
     * @var string
     */
    public $fromName;
    /**
     * @var Message
     */
    public $originalMessage;
    /**
     * @var array
     */
    public $recipients;
    /**
     * @var string
     */
    public $subject;
    /**
     * @var string
     */
    public $body;
    /**
     * @var array
     */
    public $attachments;
    /**
     * Message model once created.
     * @var Message|null
     */
    public $message;
    /**
     * @var Translator
     */
    public $translator;

    /**
     * Create a new event instance.
     *
     * @param Translator $translator
     * @param string $fromAddress
     * @param string $fromName
     * @param Message $originalMessage
     * @param array $recipients
     * @param string $subject
     * @param string $body
     * @param array $attachments
     * @param Message $message
     */
    public function __construct(Translator $translator, $fromAddress, $fromName, $originalMessage, $recipients, $subject, $body, $attachments, $message = null)
    {
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->originalMessage = $originalMessage;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
        $this->message = $message;
        $this->translator = $translator;
    }

}
