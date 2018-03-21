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

class ReplyReceived
{
    use Dispatchable, SerializesModels;

    /**
     * Reply Message once created.
     *
     * @var Message
     */
    public $message;
    /**
     * @var Translator
     */
    public $translator;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param Translator $translator
     */
    public function __construct(Message $message, Translator $translator)
    {
        $this->translator = $translator;
        $this->message = $message;
    }

}
