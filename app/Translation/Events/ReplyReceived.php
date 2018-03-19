<?php

namespace App\Translation\Events;

use App\Translation\Contracts\Translator;
use App\Translation\Message;
use App\Translation\PostmarkInboundMailRequest;
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
     * @var PostmarkInboundMailRequest
     */
    public $postmarkRequest;
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
     * @param PostmarkInboundMailRequest $postmarkRequest
     * @param Message $originalMessage
     * @param Translator $translator
     */
    public function __construct(PostmarkInboundMailRequest $postmarkRequest, Message $originalMessage, Translator $translator)
    {
        $this->postmarkRequest = $postmarkRequest;
        $this->translator = $translator;
        $this->originalMessage = $originalMessage;
    }

}
