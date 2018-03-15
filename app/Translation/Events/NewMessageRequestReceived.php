<?php

namespace App\Translation\Events;

use App\Http\Requests\CreateMessageRequest;
use App\Translation\Contracts\Translator;
use App\Translation\Message;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageRequestReceived
{
    use Dispatchable, SerializesModels;

    /**
     * @var Message
     */
    public $message;
    /**
     * @var Translator
     */
    public $translator;

    /**
     * NewMessageRequestReceived constructor.
     * @param Message $message
     * @param Translator $translator
     */
    public function __construct(Message $message, Translator $translator)
    {
        $this->translator = $translator;
        $this->message = $message;
    }
}
