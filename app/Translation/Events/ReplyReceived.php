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
     * @var Request
     */
    public $inboundMailRequest;
    /**
     * @var Translator
     */
    public $translator;
    /**
     * Reply Message once created.
     *
     * @var Message
     */
    public $message;
    /**
     * Message being replied to.
     *
     * @var Message
     */
    public $originalMessage;

    /**
     * Create a new event instance.
     *
     * @param InboundMailRequest $inboundMailRequest
     * @param Message $originalMessage
     * @param Translator $translator
     */
    public function __construct(InboundMailRequest $inboundMailRequest, Message $originalMessage, Translator $translator)
    {
        $this->inboundMailRequest = $inboundMailRequest;
        $this->translator = $translator;
        $this->originalMessage = $originalMessage;
    }

}
