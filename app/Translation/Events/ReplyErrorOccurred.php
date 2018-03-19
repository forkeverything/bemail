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
     * The reply Message that failed to translate.
     *
     * @var Message
     */
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
