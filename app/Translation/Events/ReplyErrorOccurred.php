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

class ReplyErrorOccurred
{
    use Dispatchable, SerializesModels;

    /**
     * Email address of sender who sent reply.
     *
     * @var String
     */
    public $from;

    /**
     * Message that the reply was intended for.
     *
     *
     * @var Message
     */
    public $originalMessage;

    /**
     * Reply subject
     *
     * @var String
     */
    public $subject;

    /**
     * Reply message body
     *
     * @var String
     */
    public $body;

    /**
     * Create a new event instance.
     *
     * @param $from
     * @param Message $originalMessage
     * @param $subject
     * @param $body
     */
    public function __construct($from, Message $originalMessage, $subject, $body)
    {
        $this->from = $from;
        $this->originalMessage = $originalMessage;
        $this->subject = $subject;
        $this->body = $body;
    }
}
