<?php

namespace App\Translation\Events;

use App\Contracts\InboundMail\InboundMailRequest;
use App\Contracts\Translation\Translator;
use App\Translation\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReplyMessageCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Reply Message once created.
     *
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

}
