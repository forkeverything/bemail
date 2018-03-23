<?php

namespace App\Translation\Events;

use App\Http\Requests\CreateMessageRequest;
use App\Contracts\Translation\Translator;
use App\Translation\Message;
use App\Translation\Message\NewMessageFields;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Message being translated.
     *
     * @var Message
     */
    public $message;

    /**
     * NewMessageCreated constructor.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
