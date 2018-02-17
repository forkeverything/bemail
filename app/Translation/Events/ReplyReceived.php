<?php

namespace App\Translation\Events;

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

    public $fromAddress;
    public $fromName;
    public $originalMessage;
    public $recipients;
    public $subject;
    public $body;
    public $attachments;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param string $fromAddress
     * @param string $fromName
     * @param Message $originalMessage
     * @param array $recipients
     * @param string $subject
     * @param string $body
     * @param array $attachments
     * @param Message $message
     */
    public function __construct($fromAddress, $fromName, $originalMessage, $recipients, $subject, $body, $attachments, $message = null)
    {
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->originalMessage = $originalMessage;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
        $this->message = $message;
    }

}
